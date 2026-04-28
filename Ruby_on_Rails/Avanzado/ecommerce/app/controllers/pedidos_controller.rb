class PedidosController < ApplicationController
  before_action :authenticate_usuario!
  before_action :set_pedido, only: %i[show cancel]

  def index
    @pedidos = current_usuario.pedidos.includes(:items_pedido, :productos).recientes
  end

  def show; end

  def create
    @pedido = current_usuario.pedidos.new(estado: :pendiente)

    ActiveRecord::Base.transaction do
      carrito_params[:items].each do |item|
        producto = Producto.lock.find(item[:producto_id])
        producto.reducir_stock!(item[:cantidad].to_i)
        @pedido.items_pedido.build(
          producto: producto,
          cantidad: item[:cantidad],
          precio_unitario: producto.precio
        )
      end
      @pedido.save!
    end

    render json: @pedido, status: :created
  rescue ActiveRecord::RecordInvalid, RuntimeError => e
    render json: { error: e.message }, status: :unprocessable_entity
  end

  def cancel
    if @pedido.pendiente?
      @pedido.cancelado!
      redirect_to pedidos_path, notice: 'Pedido cancelado.'
    else
      redirect_to @pedido, alert: 'No se puede cancelar este pedido.'
    end
  end

  private

  def set_pedido
    @pedido = current_usuario.pedidos.find(params[:id])
  end

  def carrito_params
    params.require(:pedido).permit(items: %i[producto_id cantidad])
  end
end
