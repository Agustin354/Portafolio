Rails.application.routes.draw do
  root 'tareas#index'
  resources :tareas do
    member { patch :completar }
  end
end
