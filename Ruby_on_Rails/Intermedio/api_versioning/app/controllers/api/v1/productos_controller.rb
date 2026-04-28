module Api
  module V1
    class ProductosController < Api::BaseController
      def index
        render json: Producto.all.map { |p| serialize_v1(p) }
      end

      private

      def serialize_v1(producto)
        { id: producto.id, nombre: producto.nombre, precio: producto.precio }
      end
    end
  end
end
