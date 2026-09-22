"""
Inference utilities for ECOCASH waste classification.
The model is loaded once at startup (see waste_classifier.py).
This module provides image preprocessing helpers.
"""
import io
from PIL import Image


def load_image_from_bytes(data: bytes) -> Image.Image:
    """Load and validate an image from raw bytes. Returns RGB PIL Image."""
    try:
        img = Image.open(io.BytesIO(data))
        return img.convert('RGB')
    except Exception as exc:
        raise ValueError(f'Cannot decode image: {exc}') from exc


def clamp_bbox(
    x1: float,
    y1: float,
    x2: float,
    y2: float,
    img_w: int,
    img_h: int,
) -> tuple[float, float, float, float]:
    """Clamp bounding box coordinates to image dimensions."""
    return (
        max(0.0, min(x1, img_w)),
        max(0.0, min(y1, img_h)),
        max(0.0, min(x2, img_w)),
        max(0.0, min(y2, img_h)),
    )
