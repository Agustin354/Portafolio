class ArchivosController < ApplicationController
  TIPOS_PERMITIDOS = %w[image/jpeg image/png image/webp application/pdf].freeze
  TAMANO_MAXIMO   = 5.megabytes

  def create
    archivo = params[:archivo]
    return render json: { error: 'Archivo requerido' }, status: :bad_request unless archivo
    return render json: { error: 'Tipo no permitido' }, status: :unprocessable_entity unless TIPOS_PERMITIDOS.include?(archivo.content_type)
    return render json: { error: 'Tamaño máximo 5MB' }, status: :unprocessable_entity if archivo.size > TAMANO_MAXIMO

    blob = ActiveStorage::Blob.create_and_upload!(
      io:           archivo,
      filename:     archivo.original_filename,
      content_type: archivo.content_type
    )

    render json: { url: url_for(blob), nombre: blob.filename, tamano: blob.byte_size }, status: :created
  end

  def destroy
    blob = ActiveStorage::Blob.find_signed!(params[:id])
    blob.purge
    head :no_content
  end
end
