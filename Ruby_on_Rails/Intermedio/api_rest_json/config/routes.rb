Rails.application.routes.draw do
  namespace :api do
    namespace :v1 do
      resources :productos
      resources :usuarios
    end
  end
end
