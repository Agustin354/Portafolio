require 'sequel'
require 'logger'

DB = Sequel.sqlite('portfolio.db')
DB.loggers << Logger.new($stdout)

DB.create_table?(:productos) do
  primary_key :id
  String  :nombre,  null: false
  Float   :precio,  null: false
  Integer :stock,   default: 0
  String  :categoria
  DateTime :created_at, default: Sequel::CURRENT_TIMESTAMP
end

class Producto < Sequel::Model
  plugin :validation_helpers
  plugin :timestamps, update_on_create: true

  def validate
    super
    validates_presence  [:nombre, :precio]
    validates_min_value 0.01, :precio
    validates_min_value 0,    :stock
  end

  dataset_module do
    def disponibles
      where { stock > 0 }
    end

    def por_categoria(cat)
      where(categoria: cat)
    end
  end
end

puts '=== ORM Sequel ==='
puts "\nCreando productos..."
p1 = Producto.create(nombre: 'Laptop', precio: 1200.0, stock: 5, categoria: 'electronica')
p2 = Producto.create(nombre: 'Mouse',  precio: 25.0,   stock: 0, categoria: 'electronica')

puts "\nDisponibles:"
Producto.disponibles.each { |p| puts "  #{p.nombre} ($#{p.precio}) stock: #{p.stock}" }

puts "\nBúsqueda por categoría 'electronica':"
Producto.por_categoria('electronica').each { |p| puts "  #{p.nombre}" }

puts "\nActualizando stock..."
p1.update(stock: 3)
puts "  #{p1.nombre} → stock: #{p1.stock}"

puts "\nTotal productos: #{Producto.count}"
