<?php
/** @var DelivereaShipping $delivereashipping */
$delivereashipping = $GLOBALS['DELIVEREASHIPPING'];

$currentPage = $delivereashipping->getCurrentPage();
$totalPages = $delivereashipping->getTotalPages();
$orders = $delivereashipping->getOrders();
?>

<div class="gap"></div>

<div class="row">
    <div class="col-lg-12">
        <section class="tile">
            <div class="bootstrap" style="display:none" id="list-error-alert">
                <div class="alert alert-danger">
                    <button type="button" class="close">×</button>
                    <span></span>
                </div>
            </div>

            <div class="tile-body p-0">
                <div class="pull-right">
                    <button type="button" class="btn btn-success btn-send" disabled="disabled">Enviar Seleccionados
                    </button>
                </div>

                <table id="shipments-table" width="90%" class="table" border="0">
                    <thead>
                    <tr>
                        <td></td>
                        <td style="font-weight:bold">Referencia</td>
                        <td style="font-weight:bold">Cliente</td>
                        <td style="font-weight:bold">Ciudad</td>
                        <td style="font-weight: bold">Fecha Creación</td>
                        <td style="font-weight: bold">Referencia Envío</td>
                        <td style="font-weight: bold">Referencia Carrier</td>
                        <td style="font-weight: bold">Fecha Recogida</td>
                        <td style="font-weight: bold">Acciones</td>
                    </tr>
                    </thead>
                    <tbody>
                    <?php /** @var WP_Post $order */
                    foreach ($orders as $order):
                        $meta = get_post_meta($order->ID); ?>
                        <tr
                            data-shipping-reference="<?php echo $order->ID; ?>"
                            data-shipping-dlvr-ref="<?php echo $meta['_deliverea_shipping_dlvr_ref'][0] ?>"
                            data-shipping-first-name="<?php echo $meta['_shipping_first_name'][0]; ?>"
                            data-shipping-last-name="<?php echo $meta['_shipping_last_name'][0]; ?>"
                            data-shipping-address-one="<?php echo $meta['_shipping_address_1'][0]; ?>"
                            data-shipping-address-two="<?php echo $meta['_shipping_address_2'][0]; ?>"
                            data-shipping-city="<?php echo $meta['_shipping_city'][0]; ?>"
                            data-shipping-postcode="<?php echo $meta['_shipping_postcode'][0]; ?>"
                            data-shipping-country="<?php echo $meta['_shipping_country'][0]; ?>"
                            data-billing-phone="<?php echo $meta['_billing_phone'][0]; ?>"
                            data-billing-email="<?php echo $meta['_billing_email'][0]; ?>"
                            data-customs-value="<?php echo $meta['_order_total'][0]; ?>"
                        >
                            <td>
                                <?php if ($meta['_deliverea_status'][0] != 'completed'): ?>
                                    <input type="checkbox" id="<?php echo $order->ID ?>">
                                <?php endif; ?>
                            </td>
                            <td><?php echo $order->ID ?></td>
                            <td name="full-name"><?php echo $meta['_shipping_first_name'][0] . ' ' . $meta['_shipping_last_name'][0] ?></td>
                            <td name="city"><?php echo $meta['_shipping_city'][0] ?></td>
                            <td name="created"><?php echo $order->post_date ?></td>
                            <td name="shipping-reference"><?php echo $meta['_deliverea_shipping_dlvr_ref'][0]; ?></td>
                            <td name="carrier-reference"><?php echo $meta['_deliverea_shipping_carrier_ref'][0]; ?></td>
                            <td name="collection-date"><?php echo $meta['_deliverea_shipping_date'][0]; ?></td>
                            <td>
                                <?php $visibility = ($meta['_deliverea_status'][0] == 'completed' && !empty($meta['_deliverea_shipping_dlvr_ref'])) ? '' : 'hidden'; ?>
                                <i class="fa fa-file-pdf-o btn-print <?php echo $visibility; ?>"
                                   style="font-size: 1.5em" aria-hidden="true"></i>

                                <i class="fa fa fa-exclamation-triangle fa-2 hidden" data-toggle="tooltip"
                                   data-placement="top"
                                   title="Ha ocurrido un error grabando tu envío. Documenta manualmente el envío desde deliverea.com"
                                   style="font-size: 1.5em" aria-hidden="true"></i>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                    <tfoot>
                    <td class="footer-table" colspan="9" style="text-align: center">
                        <a href='/wp-admin/admin.php?page=deiverea-shipping&currentPage=<?php echo($currentPage - 1); ?>'><</a>
                        <?php for ($page = 1; $page <= $totalPages; ++$page): ?>
                            <a href='/wp-admin/admin.php?page=deiverea-shipping&currentPage=<?php echo($page); ?>'><?php echo $page ?></a>
                        <?php endfor; ?>
                        <a href='/wp-admin/admin.php?page=deiverea-shipping&currentPage=<?php echo($currentPage + 1) ?>'>></a>
                    </td>
                    </tfoot>
                </table>
            </div>
        </section>
    </div>
</div>

<!-- Shipments Modal -->
<div id="shipments-modal" class="bootstrap modal fade" data-backdrop="static" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                <h1 align="center" class="modal-title">Grabar envíos</h1>
            </div>
            <div class="modal-body">
                <div id="main-content" class="row">
                    <div class="bootstrap" style="display:none" id="modal-error-alert">
                        <div class="alert alert-danger">
                            <button type="button" class="close">×</button>
                            <span></span>
                        </div>
                    </div>

                    <div class="col-md-12 text-left">
                        <p>Para informar de la franja horaria debes considerar la hora máxima en la que el operador
                            recoge la mercancía. Puedes consultar horas máximas de recogidas a través del siguiente
                            <strong><a href="http://www.deliverea.com/files/files/horasdecortelr.pdf"
                                       target="_blank">enlace</a></strong>.
                        </p>

                        <p>La horquilla de recogida debe tener un margen mínimo de <strong>2 horas</strong>.</p>

                        <p>Si tienes dudas acerca de cómo funciona el módulo puedes contactar con nosotros a través
                            del <strong>91 489 86 72</strong> o bien <strong><a href="mailto:hello@deliverea.com">hello@deliverea.com</a></strong>
                        </p>
                    </div>
                    <div class="col-md-12">
                        <div class="col-md-4">
                            <label for="from-address-id">Punto de recogida</label>

                            <div class="select-box">
                                <select id="from-address-id" class="form-control" required>
                                    <option value="">-- Selecciona Uno --</option>
                                    <?php foreach ($delivereashipping->getPickupPoints() as $pickupPoint): ?>
                                        <option value="<?php echo $pickupPoint['ID']; ?>">
                                            <?php echo $pickupPoint['alias']; ?>
                                        </option>
                                    <?php endforeach ?>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <label for="carrier-code">Carrier</label>
                            <select class="form-control" id="carrier-code" data-reset="true" required>
                                <option value="">-- Selecciona Uno --</option>
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label for="service-code">Servicio</label>
                            <select class="form-control" id="service-code" data-reset="true" required>
                                <option>-- Selecciona Uno --</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="col-md-4">
                            <label for="collection-date">Fecha Envío</label>
                            <input type="text" class="form-control" id="collection-date"
                                   placeholder="YYYY-MM-DD" required/>
                        </div>

                        <div class="col-md-4">
                            <label for="hour-start-1">Desde:</label>
                            <select class="form-control" id="hour-start-1" data-reset="true" required>
                                <option>--</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="hour-end-1">Hasta:</label>
                            <select class="form-control" id="hour-end-1" data-reset="true" required>
                                <option>--</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div id="loader" class="row text-center" style="display:none">
                <img src="<?php echo plugins_url('img/loader.gif', dirname(__FILE__)) ?>" alt="">
            </div>
            <div class="modal-footer">
                <button class="btn btn-primary" type="submit">Enviar</button>
            </div>
        </div>
    </div>
</div>


<script type="text/javascript">
    jQuery(document).ready(function () {
        delivereaList.pickupPoints = <?php echo json_encode($delivereashipping->getPickupPoints()); ?>
    });
</script>