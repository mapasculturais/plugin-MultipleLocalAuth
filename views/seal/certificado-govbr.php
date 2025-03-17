<?php
/**
 * @var MapasCulturais\Entities\SealRelation $relation
 */
use MapasCulturais\i;

$this->layout = 'seal-relation';

$user = $relation->owner;
// eval(\psy\sh());
// $creationDate = $relation->createTimestamp->format('d/m/Y');
// $updateDate = $pontao->updateTimestamp->format('d/m/Y');

// $address = "";
// $pais = $pontao->En_Pais ?: $app->config['app.defaultCountry'];
// $pais = $pais == 'BR' ? 'Brasil' : $pais;

// if ($pontao->En_Pais && $pontao->En_Municipio && $pontao->En_Estado) {
//     $address = "
//         <p>{$pais}</p>
//         <p>{$pontao->En_Municipio}</p>
//         <p>{$pontao->En_Estado}</p>
//     ";
// } else {
//     $address = "<p>".i::__('Não se aplica')."</p>";
// }

// $app->disableAccessControl();
// $cnpj = trim($pontao->cnpj ?: '');
// $app->enableAccessControl();

// if (is_string($cnpj) && empty($cnpj)) {
//     $cnpj = I::__("Não se aplica");
// }

$this->import(' 
    mc-icon
    theme-logo
');

?>
<div class="main-app">
    <div id="print">
        <div class="govbr-certificate">
            <div class="govbr-certificate__header">
                <div class="govbr-certificate__header-left">
                    <mc-icon name="check"></mc-icon>
                    <h3><?= i::__('Certificado de Vinculação ao gov.br') ?></h3>
                </div>
                <div class="govbr-certificate__header-right">
                    <img src="<?php $this->asset('img/certificate/govbr-logo.svg'); ?>" />
                </div>
            </div>

            <div class="govbr-certificate__content">
                <p class="govbr-certificate__text">
                    <b><?= i::__('Olá') . ' ' .$user->name . '!' ?></b>

                    <br><br>
                    
                    <?= i::__(' É com grande satisfação que reconhecemos a vinculação da sua conta ao gov.br, a plataforma oficial do Governo Federal. 
                                Ao obter o Selo gov.br, você demonstrou compromisso com a segurança, praticidade e transparência no acesso aos serviços públicos digitais.');?>
                </p>
            </div>

            <div class="govbr-certificate__quote">
                <p><?= i::__('Parabéns por dar mais um passo em direção à cidadania digital!') ?></p>
                <q><i><?= i::__('A inovação e a tecnologia estão a serviço de um governo mais próximo do cidadão.') ?></i></q>
                <br>
                <p class="govbr-certificate__date">
                    <b><?= i::__('Data de emissão:') ?></b>
                    <?= date_format($relation->createTimestamp, "d/m/Y") ?>
                </p>
            </div>

            <div class="govbr-certificate__footer">
                <div class="govbr-certificate__profile-link">
                    <a href="<?= $app->createUrl('agent', 'single', [$user->id]) ?>" class="govbr-certificate__profile-click hide">
                        <mc-icon name="cursor-click" />
                    </a>

                    <div class="govbr-certificate__profile-qrcode">
                    <vue-qrcode 
                        value="<?= $app->createUrl('agent', 'single', [$user->id]) ?>" 
                        :options="{
                            color: {
                                dark: '#000000',
                                light: '#FFFFFF',
                            },
                            width: 60, 
                            margin: 0,
                        }"
                        tag="svg">
                    </vue-qrcode>
                    </div>

                    <div class="govbr-certificate__profile-text">
                        <?= i::__('<span class="hide">Clique no botão <br> ou</span> escaneie o QR-Code <br> para ver o perfil completo.') ?>
                    </div>
                </div>

                <div class="govbr-certificate__footer-right">
                    <theme-logo href="<?= $app->createUrl('site', 'index') ?>"></theme-logo>
                </div>
            </div>
        </div>

    </div>
</div>