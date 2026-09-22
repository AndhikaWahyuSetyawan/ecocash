from pydantic import BaseModel, Field
class BBox(BaseModel):
    x1: float; y1: float; x2: float; y2: float
class Detection(BaseModel):
    class_id: int; class_name: str; confidence: float; bbox: BBox
class PredictionResponse(BaseModel):
    success: bool; detections: list[Detection]; count: int; processing_time_ms: int
