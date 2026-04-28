module Api
  module V2
    class ProductosController < Api::BaseController
      def index
        render json: Producto.includes(:categoria).map { |p| serialize_v2(p) }
      end

      private

      def serialize_v2(producto)
        {
          id:        producto.id,
          nombre:    producto.nombre,
          precio:    producto.precio,
          stock:     producto.stock,
          categoria: producto.categoria&.nombre,
          creado_en: producto.created_at
        }
      end
    end
  end
end
