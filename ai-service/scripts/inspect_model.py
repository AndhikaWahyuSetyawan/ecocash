from pathlib import Path
from ultralytics import YOLO
path=Path(__file__).parents[1]/'models'/'yolov8n-waste-12cls-best.pt'
model=YOLO(path)
print({'model_type':type(model.model).__name__,'names':model.names,'class_count':len(model.names),'overrides':model.overrides})
