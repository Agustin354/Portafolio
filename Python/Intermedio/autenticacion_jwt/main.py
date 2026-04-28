from fastapi import FastAPI, HTTPException, Depends
from fastapi.security import HTTPBearer, HTTPAuthorizationCredentials
from pydantic import BaseModel
import jwt
import bcrypt
from datetime import datetime, timedelta

app = FastAPI(title="Auth JWT")
bearer = HTTPBearer()

SECRET = "clave_secreta_cambiar_en_produccion"
ALGORITMO = "HS256"

usuarios_db: dict[str, dict] = {}

class RegistroSchema(BaseModel):
    email: str
    password: str

class LoginSchema(BaseModel):
    email: str
    password: str

def crear_token(email: str) -> str:
    payload = {"sub": email, "exp": datetime.utcnow() + timedelta(hours=1)}
    return jwt.encode(payload, SECRET, algorithm=ALGORITMO)

def verificar_token(cred: HTTPAuthorizationCredentials = Depends(bearer)) -> str:
    try:
        payload = jwt.decode(cred.credentials, SECRET, algorithms=[ALGORITMO])
        return payload["sub"]
    except jwt.ExpiredSignatureError:
        raise HTTPException(401, "Token expirado")
    except jwt.InvalidTokenError:
        raise HTTPException(401, "Token inválido")

@app.post("/registro", status_code=201)
def registro(data: RegistroSchema):
    if data.email in usuarios_db:
        raise HTTPException(400, "Email ya registrado")
    hashed = bcrypt.hashpw(data.password.encode(), bcrypt.gensalt())
    usuarios_db[data.email] = {"email": data.email, "password": hashed}
    return {"mensaje": "Usuario creado"}

@app.post("/login")
def login(data: LoginSchema):
    usuario = usuarios_db.get(data.email)
    if not usuario or not bcrypt.checkpw(data.password.encode(), usuario["password"]):
        raise HTTPException(401, "Credenciales inválidas")
    return {"token": crear_token(data.email)}

@app.get("/perfil")
def perfil(email: str = Depends(verificar_token)):
    return {"email": email, "mensaje": "Token válido"}
