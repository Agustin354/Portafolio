# Atributos con validación via metaprogramación
module Validable
  def self.included(base)
    base.instance_variable_set(:@validaciones, {})
    base.extend(ClassMethods)
  end

  module ClassMethods
    def atributo(nombre, tipo:, requerido: false, min: nil, max: nil)
      attr_accessor nombre
      @validaciones[nombre] = { tipo: tipo, requerido: requerido, min: min, max: max }
    end

    def validaciones = @validaciones
  end

  def valido?
    errores.empty?
  end

  def errores
    self.class.validaciones.each_with_object([]) do |(campo, reglas), lista|
      valor = send(campo)
      lista << "#{campo} es requerido" if reglas[:requerido] && valor.nil?
      next if valor.nil?
      lista << "#{campo} debe ser #{reglas[:tipo]}" unless valor.is_a?(reglas[:tipo])
      lista << "#{campo} mínimo #{reglas[:min]}" if reglas[:min] && valor < reglas[:min]
      lista << "#{campo} máximo #{reglas[:max]}" if reglas[:max] && valor > reglas[:max]
    end
  end
end

# Generador de métodos dinámicos
module Queryable
  def self.included(base)
    base.extend(ClassMethods)
  end

  module ClassMethods
    def scope(nombre, &bloque)
      define_method(nombre) { instance_exec(&bloque) }
    end

    def find_by_campos(*campos)
      campos.each do |campo|
        define_method("find_by_#{campo}") do |valor|
          self.class.all.find { |obj| obj.send(campo) == valor }
        end
      end
    end
  end
end

class Producto
  include Validable

  atributo :nombre, tipo: String, requerido: true
  atributo :precio, tipo: Float,  requerido: true, min: 0.0
  atributo :stock,  tipo: Integer, min: 0

  def initialize(nombre:, precio:, stock: 0)
    @nombre = nombre
    @precio = precio
    @stock  = stock
  end

  def to_s = "#{nombre} ($#{precio}) stock:#{stock}"
end

puts '=== Metaprogramación ==='
p1 = Producto.new(nombre: 'Laptop', precio: 1200.0, stock: 5)
p2 = Producto.new(nombre: '',       precio: -10.0)

puts "p1 válido? #{p1.valido?}"
puts "p2 errores: #{p2.errores}"
