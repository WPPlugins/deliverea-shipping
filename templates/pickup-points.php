<?php
/** @var DelivereaShipping $delivereashipping */
$delivereashipping = $GLOBALS['DELIVEREASHIPPING'];
$pickupPoints = $delivereashipping->getPickupPoints();
?>

<div class="gap"></div>

<h1>Puntos de Recogida</h1>

<div class="row">
    <div class="col-lg-12">
        <div class="bootstrap" style="display:none" id="list-error-alert">
            <div class="alert alert-danger">
                <button type="button" class="close">×</button>
                <span></span>
            </div>
        </div>

        <div class="tile-body p-0">
            <div class="pull-right">
                <button type="button" class="btn btn-success btn-add">Nuevo Punto de Recogida</button>
            </div>

            <table id="pickup-points-table" width="90%" class="table" border="0">
                <thead>
                <tr>
                    <td style="font-weight:bold">Alias</td>
                    <td style="font-weight:bold">Attn.</td>
                    <td style="font-weight:bold">Dirección</td>
                    <td style="font-weight:bold">Ciudad</td>
                    <td style="font-weight: bold">Código Postal</td>
                    <td style="font-weight: bold">País</td>
                    <td style="font-weight: bold">Observaciones</td>
                    <td style="font-weight: bold">Acciones</td>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($pickupPoints as $pickupPoint): ?>
                    <tr data-id="<?php echo $pickupPoint['ID']; ?>">
                        <td><?php echo $pickupPoint['alias']; ?></td>
                        <td><?php echo $pickupPoint['attn']; ?></td>
                        <td><?php echo $pickupPoint['address']; ?></td>
                        <td><?php echo $pickupPoint['city']; ?></td>
                        <td><?php echo $pickupPoint['zip_code']; ?></td>
                        <td><?php echo $pickupPoint['country']; ?></td>
                        <td><?php echo $pickupPoint['observations']; ?></td>
                        <td><i class="fa fa-times fa-2 btn-delete" aria-hidden="true"></i>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Pickup Point Modal -->
<div id="pickup-point-modal" class="bootstrap modal fade" data-backdrop="static" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                <h1 align="center" class="modal-title">Nuevo Punto de Recogida</h1>
            </div>
            <div class="modal-body">
                <div id="main-content" class="row">
                    <div class="bootstrap" style="display:none" id="modal-error-alert">
                        <div class="alert alert-danger">
                            <button type="button" class="close">×</button>
                            <span></span>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="alias">Alias/Nombre*</label>
                                <input id="alias" name="alias" type="text" class="form-control" data-required="true"/>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="attn">Atención de</label>
                                <input id="attn" name="attn" type="text" class="form-control"/>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="phone">Teléfono*</label>
                                <input id="phone" name="phone" type="text" class="form-control" data-required="true"/>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="email">Correo Electrónico*</label>
                                <input id="email" name="email" type="text" class="form-control" data-required="true"/>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="address">Dirección*</label>
                                <input id="address" name="address" type="text" class="form-control"
                                       data-required="true"/>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="city">Ciudad*</label>
                                <input id="city" name="city" type="text" class="form-control" data-required="true"/>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="zip_code">Código Postal*</label>
                                <input id="zip_code" name="zip_code" type="text" class="form-control"
                                       data-required="true"/>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="country">País*</label>
                                <select id="country" name="country" class="form-control" data-required="true">
                                    <?php foreach ($delivereashipping->getCountries() as $countryCode => $country): ?>
                                        <option value="<?php echo $countryCode; ?>"><?php echo $country ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="observations">Observaciones</label>
                                <textarea id="observations" name="observations" class="form-control"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div id="loader" class="row text-center" style="display:none">
                <img src="<?php echo plugins_url('img/loader.gif', dirname(__FILE__)) ?>" alt="">
            </div>
            <div class="modal-footer">
                <button class="btn btn-primary" type="submit">Añadir</button>
            </div>
        </div>
    </div>
</div>