class ApplicationController < ActionController::Base
  before_action :authenticate_usuario!

  rescue_from ActiveRecord::RecordNotFound do |e|
    respond_to do |format|
      format.html { redirect_to root_path, alert: 'Registro no encontrado.' }
      format.json { render json: { error: e.message }, status: :not_found }
    end
  end

  def after_sign_in_path_for(resource)
    dashboard_path
  end

  def after_sign_out_path_for(resource_or_scope)
    new_usuario_session_path
  end
end
