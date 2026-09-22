# ECOCASH AI Service

FastAPI inference service using the required `yolov8n-waste-12cls-best.pt` model. The model is downloaded at setup time and is ignored by Git. Run `python scripts/download_model.py`, then `python scripts/inspect_model.py` before starting `uvicorn app.main:app --host 0.0.0.0 --port 8001`.

Model source and attribution: [daylans/waste-classification-yolov8-ken](https://huggingface.co/daylans/waste-classification-yolov8-ken). Review the repository's current model license before distribution. ECOCASH does not claim ownership of the pretrained model.
