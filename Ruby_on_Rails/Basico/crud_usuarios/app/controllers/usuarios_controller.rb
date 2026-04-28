class UsuariosController < ApplicationController
  before_action :set_usuario, only: %i[show edit update destroy]

  def index   = @usuarios = Usuario.activos.order(:nombre)
  def show;   end
  def new     = @usuario = Usuario.new
  def edit;   end

  def create
    @usuario = Usuario.new(usuario_params)
    @usuario.save ? redirect_to(@usuario, notice: 'Usuario creado.') : render(:new, status: :unprocessable_entity)
  end

  def update
    @usuario.update(usuario_params.except(:password).merge(usuario_params.slice(:password).reject { |_, v| v.blank? })) ?
      redirect_to(@usuario, notice: 'Actualizado.') : render(:edit, status: :unprocessable_entity)
  end

  def destroy
    @usuario.update(activo: false)
    redirect_to usuarios_path, notice: 'Usuario desactivado.'
  end

  private

  def set_usuario     = @usuario = Usuario.find(params[:id])
  def usuario_params  = params.require(:usuario).permit(:nombre, :email, :password, :rol)
end
