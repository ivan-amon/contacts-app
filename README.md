# Proyecto final "Todo sobre la web con PHP"

## Mejoras añadidas

Se han implementado las siguientes mejoras en "contacts-app":

- **Soporte para múltiples direcciones por contacto**: Ahora cada contacto puede tener una o varias direcciones asociadas.
- **CRUD para direcciones**: Se ha añadido la funcionalidad completa de Crear, Leer, Actualizar y Eliminar direcciones para cada contacto.
- **Seguridad añadida**: Se ha tenido en cuenta la seguridad para evitar inyecciones SQL y enviar códigos 403 para que nadie pueda modificar nada mas que el usuario que tiene sus contactos y direcciones.
- **Mensajes flash**: Cada vez que se hace una accion CRUD se mostrará un mensaje en la pantalla

## Notas y Consideraciones

- Para poder añadir una direccion, hay que tener un contacto como mínimo creado.
- En los ON UPDATE y ON DELETE se ha utilizado CASCADE, por lo que cada vez que se borre un contacto se borraran todas sus direcciones.


