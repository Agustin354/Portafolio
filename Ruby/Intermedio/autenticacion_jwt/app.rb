require 'sinatra'
require 'sinatra/json'
require 'jwt'
require 'bcrypt'
require 'json'

set :port, 4568

SECRET  = 'clave_secreta_cambiar_en_produccion'
ALGORITMO = 'HS256'
USUARIOS = {}

before { content_type :json }

helpers do
  def body_json
    JSON.parse(request.body.read, symbolize_names: true)
  end

  def token_payload
    auth = request.env['HTTP_AUTHORIZATION']&.split(' ')&.last
    halt 401, json(error: 'Token requerido') unless auth
    JWT.decode(auth, SECRET, true, algorithm: ALGORITMO).first
  rescue JWT::DecodeError
    halt 401, json(error: 'Token inválido o expirado')
  end

  def generar_token(email)
    payload = { sub: email, exp: Time.now.to_i + 3600 }
    JWT.encode(payload, SECRET, ALGORITMO)
  end
end

post '/registro' do
  data = body_json
  halt 400, json(error: 'Email ya registrado') if USUARIOS[data[:email]]
  USUARIOS[data[:email]] = { email: data[:email], password: BCrypt::Password.create(data[:password]) }
  status 201
  json mensaje: 'Usuario creado'
end

post '/login' do
  data    = body_json
  usuario = USUARIOS[data[:email]]
  halt 401, json(error: 'Credenciales inválidas') unless usuario&.then { BCrypt::Password.new(_1[:password]) == data[:password] }
  json token: generar_token(data[:email])
end

get '/perfil' do
  payload = token_payload
  json email: payload['sub'], mensaje: 'Token válido'
end
