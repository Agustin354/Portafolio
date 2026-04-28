from rest_framework import viewsets, permissions, filters
from rest_framework.decorators import action
from rest_framework.response import Response
from django_filters.rest_framework import DjangoFilterBackend
from .models import Producto
from .serializers import ProductoSerializer

class ProductoViewSet(viewsets.ModelViewSet):
    queryset = Producto.objects.all()
    serializer_class = ProductoSerializer
    permission_classes = [permissions.IsAuthenticatedOrReadOnly]
    filter_backends = [DjangoFilterBackend, filters.SearchFilter, filters.OrderingFilter]
    filterset_fields = ['categoria', 'disponible']
    search_fields = ['nombre', 'descripcion']
    ordering_fields = ['precio', 'nombre', 'created_at']

    @action(detail=False, methods=['get'])
    def disponibles(self, request):
        qs = self.get_queryset().filter(stock__gt=0)
        serializer = self.get_serializer(qs, many=True)
        return Response(serializer.data)

    @action(detail=True, methods=['post'])
    def reducir_stock(self, request, pk=None):
        producto = self.get_object()
        cantidad = request.data.get('cantidad', 1)
        if producto.stock < cantidad:
            return Response({'error': 'Stock insuficiente'}, status=400)
        producto.stock -= cantidad
        producto.save()
        return Response({'stock_restante': producto.stock})
