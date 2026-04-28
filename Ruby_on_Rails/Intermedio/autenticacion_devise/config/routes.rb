Rails.application.routes.draw do
  devise_for :usuarios, path: 'auth', path_names: {
    sign_in: 'login',
    sign_out: 'logout',
    sign_up: 'registro'
  }

  authenticate :usuario do
    get '/dashboard', to: 'dashboard#index', as: :dashboard
    resources :perfil, only: %i[show edit update]
  end

  root 'home#index'
end
