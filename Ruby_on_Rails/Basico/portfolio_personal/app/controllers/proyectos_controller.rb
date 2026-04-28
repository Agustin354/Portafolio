class ProyectosController < ApplicationController
  skip_before_action :authenticate_usuario!, only: %i[index show]
  before_action :set_proyecto, only: %i[show edit update destroy]

  def index
    @proyectos = Proyecto.publicados.includes(:tecnologias).order(orden: :asc)
  end

  def show; end

  def new    = @proyecto = Proyecto.new
  def edit;  end

  def create
    @proyecto = Proyecto.new(proyecto_params)
    @proyecto.save ? redirect_to(@proyecto, notice: 'Proyecto agregado.') : render(:new, status: :unprocessable_entity)
  end

  def update
    @proyecto.update(proyecto_params) ? redirect_to(@proyecto, notice: 'Actualizado.') : render(:edit, status: :unprocessable_entity)
  end

  def destroy
    @proyecto.destroy
    redirect_to proyectos_path, notice: 'Eliminado.'
  end

  private

  def set_proyecto   = @proyecto = Proyecto.find(params[:id])
  def proyecto_params = params.require(:proyecto).permit(:titulo, :descripcion, :url, :imagen, :publicado, :orden, tecnologia_ids: [])
end
