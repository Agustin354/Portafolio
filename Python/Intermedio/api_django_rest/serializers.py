from rest_framework import serializers
from .models import Producto

class ProductoSerializer(serializers.ModelSerializer):
    disponible_stock = serializers.SerializerMethodField()

    class Meta:
        model = Producto
        fields = '__all__'
        read_only_fields = ['created_at']

    def get_disponible_stock(self, obj):
        return obj.stock > 0

    def validate_precio(self, value):
        if value <= 0:
            raise serializers.ValidationError("El precio debe ser mayor a 0.")
        return value
