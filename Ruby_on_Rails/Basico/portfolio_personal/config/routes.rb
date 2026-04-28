Rails.application.routes.draw do
  root 'proyectos#index'
  resources :proyectos
  get '/sobre-mi', to: 'paginas#sobre_mi', as: :sobre_mi
  get '/contacto',  to: 'paginas#contacto',  as: :contacto
  post '/contacto', to: 'paginas#enviar_contacto'
end
