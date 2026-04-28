Rails.application.routes.draw do
  resources :archivos, only: %i[create destroy]
end
