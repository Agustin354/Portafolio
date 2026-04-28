from fastapi import FastAPI, HTTPException
from pydantic import BaseModel
from typing import Optional
import uvicorn

app = FastAPI(title="API REST Portfolio")

class Item(BaseModel):
    id: Optional[int] = None
    nombre: str
    precio: float
    stock: int

db: dict[int, Item] = {}
_next_id = 1

@app.get("/items")
def listar():
    return list(db.values())

@app.get("/items/{id}")
def obtener(id: int):
    if id not in db:
        raise HTTPException(404, "Item no encontrado")
    return db[id]

@app.post("/items", status_code=201)
def crear(item: Item):
    global _next_id
    item.id = _next_id
    db[_next_id] = item
    _next_id += 1
    return item

@app.put("/items/{id}")
def actualizar(id: int, item: Item):
    if id not in db:
        raise HTTPException(404, "Item no encontrado")
    item.id = id
    db[id] = item
    return item

@app.delete("/items/{id}")
def eliminar(id: int):
    if id not in db:
        raise HTTPException(404, "Item no encontrado")
    del db[id]
    return {"mensaje": "Eliminado"}

if __name__ == "__main__":
    uvicorn.run(app, host="0.0.0.0", port=8000)
