require 'sinatra'
require 'sinatra/json'
require 'json'

set :port, 4567

productos = [
  { id: 1, nombre: 'Laptop', precio: 1200.0 },
  { id: 2, nombre: 'Mouse', precio: 25.0 }
]
next_id = 3

before { content_type :json }

get '/productos' do
  json productos
end

get '/productos/:id' do
  item = productos.find { |p| p[:id] == params[:id].to_i }
  item ? json(item) : [404, json({ error: 'No encontrado' })]
end

post '/productos' do
  data = JSON.parse(request.body.read, symbolize_names: true)
  data[:id] = next_id
  next_id += 1
  productos << data
  [201, json(data)]
end

put '/productos/:id' do
  idx = productos.index { |p| p[:id] == params[:id].to_i }
  return [404, json({ error: 'No encontrado' })] unless idx

  data = JSON.parse(request.body.read, symbolize_names: true)
  data[:id] = params[:id].to_i
  productos[idx] = data
  json data
end

delete '/productos/:id' do
  eliminado = productos.reject! { |p| p[:id] == params[:id].to_i }
  eliminado ? json({ mensaje: 'Eliminado' }) : [404, json({ error: 'No encontrado' })]
end
