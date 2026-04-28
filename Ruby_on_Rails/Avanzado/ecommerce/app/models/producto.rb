class Producto < ApplicationRecord
  belongs_to :categoria
  has_many :items_pedido
  has_many :pedidos, through: :items_pedido
  has_one_attached :imagen

  validates :nombre, presence: true
  validates :precio, numericality: { greater_than: 0 }
  validates :stock, numericality: { greater_than_or_equal_to: 0 }

  scope :disponibles, -> { where('stock > 0') }
  scope :por_categoria, ->(cat) { joins(:categoria).where(categorias: { nombre: cat }) }

  def disponible?
    stock > 0
  end

  def reducir_stock!(cantidad)
    raise 'Stock insuficiente' if stock < cantidad
    decrement!(:stock, cantidad)
  end
end
