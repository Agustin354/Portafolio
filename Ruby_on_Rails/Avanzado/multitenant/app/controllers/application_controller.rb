class ApplicationController < ActionController::Base
  before_action :set_tenant

  private

  def set_tenant
    subdominio = request.subdomain
    tenant = Tenant.find_by(subdominio: subdominio)
    return render json: { error: 'Tenant no encontrado' }, status: :not_found unless tenant

    Tenant.actual = tenant
    ActsAsTenant.current_tenant = tenant
  end
end
