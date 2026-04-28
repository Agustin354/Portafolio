class TareasController < ApplicationController
  before_action :set_tarea, only: %i[show edit update destroy completar]

  def index
    @tareas = Tarea.por_prioridad
  end

  def show; end

  def new
    @tarea = Tarea.new
  end

  def create
    @tarea = Tarea.new(tarea_params)
    if @tarea.save
      redirect_to @tarea, notice: 'Tarea creada.'
    else
      render :new, status: :unprocessable_entity
    end
  end

  def update
    if @tarea.update(tarea_params)
      redirect_to @tarea, notice: 'Tarea actualizada.'
    else
      render :edit, status: :unprocessable_entity
    end
  end

  def completar
    @tarea.completada!
    redirect_to tareas_path, notice: 'Tarea completada.'
  end

  def destroy
    @tarea.destroy
    redirect_to tareas_path, notice: 'Tarea eliminada.'
  end

  private

  def set_tarea
    @tarea = Tarea.find(params[:id])
  end

  def tarea_params
    params.require(:tarea).permit(:titulo, :descripcion, :estado, :prioridad, :fecha_limite)
  end
end
