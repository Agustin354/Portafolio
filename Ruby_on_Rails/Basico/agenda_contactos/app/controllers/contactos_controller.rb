class ContactosController < ApplicationController
  before_action :set_contacto, only: %i[show edit update destroy]

  def index
    @contactos = params[:q] ? Contacto.buscar(params[:q]) : Contacto.order(:nombre)
  end

  def show;  end
  def new    = @contacto = Contacto.new
  def edit;  end

  def create
    @contacto = Contacto.new(contacto_params)
    @contacto.save ? redirect_to(@contacto, notice: 'Contacto agregado.') : render(:new, status: :unprocessable_entity)
  end

  def update
    @contacto.update(contacto_params) ? redirect_to(@contacto, notice: 'Actualizado.') : render(:edit, status: :unprocessable_entity)
  end

  def destroy
    @contacto.destroy
    redirect_to contactos_path, notice: 'Eliminado.'
  end

  private

  def set_contacto    = @contacto = Contacto.find(params[:id])
  def contacto_params = params.require(:contacto).permit(:nombre, :email, :telefono, :direccion, :notas)
end
