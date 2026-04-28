# DSL para definir rutas de API
class ApiDSL
  attr_reader :rutas

  def initialize
    @rutas     = []
    @prefijo   = ''
    @middlewares = []
  end

  def prefijo(path)
    @prefijo = path
  end

  def middleware(*mws)
    @middlewares.concat(mws)
  end

  def get(path, &bloque)    = agregar(:GET,    path, bloque)
  def post(path, &bloque)   = agregar(:POST,   path, bloque)
  def put(path, &bloque)    = agregar(:PUT,    path, bloque)
  def delete(path, &bloque) = agregar(:DELETE, path, bloque)

  def grupo(path, &bloque)
    anterior = @prefijo
    @prefijo = "#{@prefijo}#{path}"
    instance_eval(&bloque)
    @prefijo = anterior
  end

  private

  def agregar(metodo, path, bloque)
    @rutas << { metodo: metodo, path: "#{@prefijo}#{path}",
                handler: bloque, middlewares: @middlewares.dup }
  end
end

def api(&bloque)
  dsl = ApiDSL.new
  dsl.instance_eval(&bloque)
  dsl
end

# Uso del DSL
mi_api = api do
  prefijo '/api/v1'
  middleware :autenticacion, :logging

  get('/salud') { { status: 'ok' } }

  grupo '/productos' do
    get('/')          { 'listar productos' }
    post('/')         { 'crear producto'   }
    get('/:id')       { 'obtener producto' }
    put('/:id')       { 'actualizar'       }
    delete('/:id')    { 'eliminar'         }
  end

  grupo '/usuarios' do
    middleware :admin
    get('/')    { 'listar usuarios' }
    delete('/:id') { 'eliminar usuario' }
  end
end

puts '=== DSL Personalizado — Rutas definidas ==='
mi_api.rutas.each do |r|
  puts "#{r[:metodo].to_s.ljust(7)} #{r[:path].ljust(30)} middlewares: #{r[:middlewares].join(', ')}"
end
