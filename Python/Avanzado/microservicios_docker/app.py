from flask import Flask, jsonify, request
import os

app = Flask(__name__)

SERVICIO = os.getenv("SERVICIO", "productos")
productos = [
    {"id": 1, "nombre": "Laptop", "precio": 1200.0},
    {"id": 2, "nombre": "Mouse", "precio": 25.0},
]

@app.get("/health")
def health():
    return jsonify({"status": "ok", "servicio": SERVICIO})

@app.get("/productos")
def listar():
    return jsonify(productos)

@app.get("/productos/<int:id>")
def obtener(id):
    item = next((p for p in productos if p["id"] == id), None)
    return jsonify(item) if item else (jsonify({"error": "no encontrado"}), 404)

@app.post("/productos")
def crear():
    data = request.json
    data["id"] = max(p["id"] for p in productos) + 1
    productos.append(data)
    return jsonify(data), 201

if __name__ == "__main__":
    app.run(host="0.0.0.0", port=5000)
