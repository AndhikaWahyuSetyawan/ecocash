"""
ECOCASH AI service tests.
Run with: pytest ai-service/tests/test_api.py
Requires: pytest, httpx, Pillow
These tests use TestClient and do NOT require a running server or the actual model.
Model-dependent tests are marked and skipped when model file is absent.
"""
import io
from pathlib import Path

import pytest
from PIL import Image

MODEL_PATH = Path(__file__).parents[1] / 'models' / 'yolov8n-waste-12cls-best.pt'
MODEL_AVAILABLE = MODEL_PATH.exists()

# Only import the app when the model is present to avoid load failure.
if MODEL_AVAILABLE:
    from fastapi.testclient import TestClient
    from app.main import app

    client = TestClient(app)


def make_test_image(width: int = 640, height: int = 480, color: tuple = (100, 150, 80)) -> bytes:
    """Create a minimal valid JPEG image in memory."""
    img = Image.new('RGB', (width, height), color=color)
    buf = io.BytesIO()
    img.save(buf, format='JPEG')
    return buf.getvalue()


@pytest.mark.skipif(not MODEL_AVAILABLE, reason='Model file not present')
def test_health():
    response = client.get('/health')
    assert response.status_code == 200
    data = response.json()
    assert data['status'] == 'ok'
    assert data['model_loaded'] is True


@pytest.mark.skipif(not MODEL_AVAILABLE, reason='Model file not present')
def test_predict_valid_image():
    image_bytes = make_test_image()
    response = client.post(
        '/api/v1/predict',
        files={'image': ('test.jpg', image_bytes, 'image/jpeg')},
    )
    assert response.status_code == 200
    data = response.json()
    assert data['success'] is True
    assert isinstance(data['detections'], list)
    assert isinstance(data['count'], int)
    assert data['count'] == len(data['detections'])
    assert 'processing_time_ms' in data


@pytest.mark.skipif(not MODEL_AVAILABLE, reason='Model file not present')
def test_predict_invalid_file():
    response = client.post(
        '/api/v1/predict',
        files={'image': ('bad.txt', b'not an image', 'text/plain')},
    )
    assert response.status_code in (422, 400)


@pytest.mark.skipif(not MODEL_AVAILABLE, reason='Model file not present')
def test_predict_confidence_filtering():
    """High confidence threshold should return fewer or no detections."""
    image_bytes = make_test_image()
    response = client.post(
        '/api/v1/predict',
        files={'image': ('test.jpg', image_bytes, 'image/jpeg')},
        data={'confidence': '0.99'},
    )
    assert response.status_code == 200
    data = response.json()
    # All returned detections must meet the requested confidence threshold.
    for det in data['detections']:
        assert det['confidence'] >= 0.99


@pytest.mark.skipif(not MODEL_AVAILABLE, reason='Model file not present')
def test_detection_structure():
    """Each detection must carry all required fields."""
    image_bytes = make_test_image()
    response = client.post(
        '/api/v1/predict',
        files={'image': ('test.jpg', image_bytes, 'image/jpeg')},
    )
    assert response.status_code == 200
    for det in response.json()['detections']:
        assert 'class_id' in det
        assert 'class_name' in det
        assert 'confidence' in det
        assert 'bbox' in det
        assert all(k in det['bbox'] for k in ('x1', 'y1', 'x2', 'y2'))
