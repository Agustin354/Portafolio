Rails.application.routes.draw do
  namespace :api do
    namespace :v1 do
      resources :productos, only: %i[index show]
    end
    namespace :v2 do
      resources :productos
      resources :categorias
    end
  end
end
