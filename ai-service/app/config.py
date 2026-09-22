from pathlib import Path
import os
MODEL_PATH = Path(os.getenv('MODEL_PATH', 'models/yolov8n-waste-12cls-best.pt'))
CONFIDENCE_THRESHOLD = float(os.getenv('CONFIDENCE_THRESHOLD', '0.25'))
IMAGE_SIZE = int(os.getenv('IMAGE_SIZE', '640'))
