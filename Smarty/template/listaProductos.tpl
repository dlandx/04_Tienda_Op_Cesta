{* Contenedor para listar todos los productos de la BBDD... *}
<div class="list_product">
    {* Obtenemos el valor enviado desde lógica de negocio (productos.php) para recorrer los productos *}
    {* Valor $listaProductos -> Array de array (tantos productos array indexado, producto - array asociativo) *}
    {assign var=result value=''} {* Creamos una variable para concatenar los resultados <p> *}

    {* from=array | item=elemento | key=key | name=ciclo foreach (acceder a las propiedades del foreach) *}
    {foreach name=pos item=producto from=$listaProductos}
        {* Asignar a cada BTN el cod del producto - para tener solo un FORM *}
        {assign var=result value=$result|cat:"<div class='c_list'><div class='c_btn'>
            <button type='submit' name='btn' value='"|cat:$producto->getCod()|cat:"'></button></div>"}
        {* Recorremos el objeto - Datos del productos 'por fila' *}
        {* Solo mostrar la columna [nombre_corto y PVP] *}
        {assign var=result value=$result|cat:"<div class='list'><span class='name'>"|cat:{$producto->getName_short()}|cat:"</span>"}
        {assign var=result value=$result|cat:"<span class='price'>"|cat:{$producto->getPrecio()}|cat:"</span></div>"}

        {assign var=result value=$result|cat:"</div>"}
    {/foreach}

    {$result} {* Resultado de la variable concatenda *}
</div>


