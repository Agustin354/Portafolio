module Types
  class QueryType < Types::BaseObject
    field :productos, [Types::ProductoType], null: false,
          description: 'Listado de todos los productos'

    field :producto, Types::ProductoType, null: true,
          description: 'Producto por ID' do
      argument :id, ID, required: true
    end

    field :pedidos_usuario, [Types::PedidoType], null: false,
          description: 'Pedidos del usuario autenticado'

    def productos
      Producto.disponibles.includes(:categoria)
    end

    def producto(id:)
      Producto.find_by(id: id)
    end

    def pedidos_usuario
      raise GraphQL::ExecutionError, 'No autenticado' unless context[:usuario]
      context[:usuario].pedidos.recientes
    end
  end
end
