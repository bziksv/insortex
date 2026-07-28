<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
?>


<style>
.footer-content {
    display: flex;
    flex-direction: column;
    gap: 20px;
    margin: 0 auto;
    width: 100%;
    max-width: 680px;
}

.footer-contacts {
    display: flex;
    justify-content: center;
    margin: 0 auto;
}

.footer-copyright {
    display: flex;
    margin: 0 auto;
    width: 50%;
    color: #fff;
    align-items: center;
    font-size: 13px;
    gap: 22px;
}

.footer-attention {
    font-size: 13px;
    margin: 0 auto;
    color: #fff;
    text-align: justify;
}

.primelogo {
    width: 40%;
}

.contacts-footer {
    display: flex;
    justify-content: center;
    margin: 0 auto;
}

.item-footer {
    display: flex;
    align-items: center;
    margin: 0 20px;
    margin-bottom: 10px;
}

.item-footer-icon {
    height: 22px;
    width: 22px;
    color: #ffffff;
    margin-right: 5px;
    position: relative;
}

.item-footer-text {
    font-size: 15px;
    line-height: 22px;
    color: #ffffff;
    text-align: center;
}

.item-footer-text:hover {
    color: #43b4e5;
}

/* ========== АДАПТИВ ========== */

/* Планшеты и узкие экраны (до 768px) */
@media (max-width: 768px) {
    .footer-copyright {
        width: 80%;
        flex-wrap: wrap;
        justify-content: center;
        text-align: center;
        gap: 15px;
    }
    .primelogo {
        width: 60%;
        max-width: 200px;
    }
    .contacts-footer {
        flex-direction: column;
        align-items: center;
        gap: 5px;
    }
    .item-footer {
        margin: 0;
        margin-bottom: 5px;
    }
    .footer-attention {
        padding: 0 15px;
    }
}

/* Мобильные телефоны (до 480px) */
@media (max-width: 480px) {
    .footer-content {
        gap: 15px;
    }
    .footer-copyright {
        width: 95%;
        flex-direction: column;
        gap: 10px;
    }
    .primelogo {
        width: 80%;
        max-width: 180px;
    }
    .contacts-footer {
        width: 100%;
    }
    .item-footer {
        width: 100%;
        justify-content: center;
        margin-bottom: 8px;
    }
    .item-footer-text {
        font-size: 14px;
    }
    .footer-attention {
        font-size: 11px;
        padding: 0 10px;
    }
}
</style>

<footer class="footer-dark">
<div class="maxwidth-theme">

<div class="footer-content">

<div class="contacts-footer">

<div class="item-footer">
<div class="item-footer-icon"><svg class="svg"><use xlink:href="/bitrix/templates/ranx-landing/assets/img/footer/icons.svg?1675149157#address"></use></svg></div>
<div class="item-footer-text">г. Воронеж, ул. Ильюшина, 3В</div>
</div>
                    
<div class="item-footer">
<div class="item-footer-icon"><svg class="svg"><use xlink:href="/bitrix/templates/ranx-landing/assets/img/footer/icons.svg?1675149157#phone"></use></svg></div>
<a class="item-footer-text" href="tel:78003507012">+7 (800) 350-70-12</a>
</div>
                    
<div class="item-footer">
<div class="item-footer-icon"><svg class="svg"><use xlink:href="/bitrix/templates/ranx-landing/assets/img/footer/icons.svg?1675149157#email"></use></svg></div>
<a href="mailto:info@example.com" class="item-footer-text">baa8814@mail.ru</a>
</div>

</div>

<div class="footer-social">

        <a class="footer-social-block theme-exclude-hover" href="https://vk.com/insortex" target="_blank" rel="nofollow" title="ВКонтакте"><svg class="svg"><use xlink:href="/bitrix/templates/ranx-landing/assets/img/footer/social.svg?1675149157#vk"></use></svg></a>

</div>

<div class="footer-attention">
	<div class="attention-text">Мы используем файлы <a target="_blank" href="/upload/politika-cookies-insortex.pdf">cookie</a> для обеспечения корректной работы сайта, сбора статистики и анализа пользовательской активности. Оставаясь на сайте, вы соглашаетесь на обработку ваших персональных данных. Вы можете в любой момент отключить сохранение cookie в настройках браузера. Также на сайте применяются <a target="_blank" href="/upload/rules-recommendation-insortex.pdf">рекомендательные технологии</a>, подробные сведения об обработке данных содержатся в соответствующей <a target="_blank" href="/upload/personal-data-insortex.pdf">Политике</a>.</div>
</div>

<div class="footer-copyright">
<div class="footer-copyright-text">2026 © Все права защищены.</div>
<div class="primelogo"><a href="https://prime-ltd.su/?from=https://insortex.ru/"><img src="http://prime-ltd.su/logo/white.svg" title="Продвижение сайтов" alt="Продвижение сайтов"></a></div>
</div>


</div>

</div>
</footer>

<!--
<style>
	.footer-content {
		display: flex;
		flex-direction: column;
		gap: 20px;
		margin: 0 auto;
		width: 100%;
		max-width: 680px;
	}

	.footer-contacts {
		display: flex;
		jutify-content: center;
		margin: 0 auto;
}

	.footer-copyright {
		display: flex;
		margin: 0 auto;
		width: 50%;
		color: #fff;
		align-items: center;
		font-size: 13px;
		gap: 22px;
	}


	.footer-attention {
		font-size: 13px;
		margin: 0 auto;
		color: #fff;
		text-align: justify;
	}

	.primelogo {
		width: 40%;
	}

	.footer-copyright-text {

	}

</style>

<footer class="footer-dark">
<div class="maxwidth-theme">

<div class="footer-content">

<div class="footer-contacts">

<div class="footer-item">
<div class="footer-item-icon"><svg class="svg"><use xlink:href="/bitrix/templates/ranx-landing/assets/img/footer/icons.svg?1675149157#address"></use></svg></div>
<div class="footer-item-text">г. Воронеж, ул. Ильюшина, 3В</div>
</div>
                    
<div class="footer-item">
<div class="footer-item-icon"><svg class="svg"><use xlink:href="/bitrix/templates/ranx-landing/assets/img/footer/icons.svg?1675149157#phone"></use></svg></div>
<a class="footer-item-text" href="tel:+89521056301">+8 (952) 105-63-01</a>
</div>
                    
<div class="footer-item">
<div class="footer-item-icon"><svg class="svg"><use xlink:href="/bitrix/templates/ranx-landing/assets/img/footer/icons.svg?1675149157#email"></use></svg></div>
<a href="mailto:info@example.com" class="footer-item-text">baa8814@mail.ru</a>
</div>

</div>

<div class="footer-social">

        <a class="footer-social-block theme-exclude-hover" href="https://vk.com/insortex" target="_blank" rel="nofollow" title="ВКонтакте"><svg class="svg"><use xlink:href="/bitrix/templates/ranx-landing/assets/img/footer/social.svg?1675149157#vk"></use></svg></a>

</div>

<div class="footer-attention">
	<div class="attention-text">Мы используем файлы <a target="_blank" href="politika-cookies-insortex.pdf">cookie</a> для обеспечения корректной работы сайта, сбора статистики и анализа пользовательской активности. Оставаясь на сайте, вы соглашаетесь на обработку ваших персональных данных. Вы можете в любой момент отключить сохранение cookie в настройках браузера. Также на сайте применяются <a target="_blank" href="/upload/rules-recommendation-insortex.pdf">рекомендательные технологии</a>, подробные сведения об обработке данных содержатся в соответствующей <a target="_blank" href="/upload/personal-data-insortex.pdf">Политике</a>.</div>
</div>

<div class="footer-copyright">
<div class="footer-copyright-text">2026 © Все права защищены.</div>
<div class="primelogo"><a href="https://prime-ltd.su/?from=https://insortex.ru/"><img src="https://prime-ltd.su/logo/white.svg" title="Продвижение сайтов" alt="Продвижение сайтов"></a></div>
</div>


</div>

</div>
</footer>

-->


<!--<footer class="footer-dark">
    <div class="maxwidth-theme">

        <div class="row">
            <div class="col-md-12 d-flex justify-content-center">

                                <div class="footer-items">

                                        <div class="footer-item">
                        <div class="footer-item-icon"><svg class="svg"><use xlink:href="/bitrix/templates/ranx-landing/assets/img/footer/icons.svg?1675149157#address"></use></svg></div>
                        <div class="footer-item-text">ул. Молодогвардейцев, 31</div>
                    </div>
                    
                                        <div class="footer-item">
                        <div class="footer-item-icon"><svg class="svg"><use xlink:href="/bitrix/templates/ranx-landing/assets/img/footer/icons.svg?1675149157#phone"></use></svg></div>
                        <a class="footer-item-text" href="tel:+79999999999">+7 (999) 999-99-99</a>
                    </div>
                    
                                        <div class="footer-item">
                        <div class="footer-item-icon"><svg class="svg"><use xlink:href="/bitrix/templates/ranx-landing/assets/img/footer/icons.svg?1675149157#email"></use></svg></div>
                        <a href="mailto:info@example.com" class="footer-item-text">info@example.com</a>
                    </div>
                    
                </div>
                
            </div>
        </div>

        <div class="row">
            <div class="col-md-12 d-flex justify-content-center">
                
<div class="footer-social">

        <a class="footer-social-block theme-exclude-hover" href="https://vk.com/ranx_ru" target="_blank" rel="nofollow" title="ВКонтакте"><svg class="svg"><use xlink:href="/bitrix/templates/ranx-landing/assets/img/footer/social.svg?1675149157#vk"></use></svg></a>
        <a class="footer-social-block theme-exclude-hover" href="https://t.me/ranx_bot" target="_blank" rel="nofollow" title="Telegram"><svg class="svg"><use xlink:href="/bitrix/templates/ranx-landing/assets/img/footer/social.svg?1675149157#telegram"></use></svg></a>
        <a class="footer-social-block theme-exclude-hover" href="https://zen.yandex.ru/id/6165733290f2af1d1f3e6bc6" target="_blank" rel="nofollow" title="Яндекс Дзен"><svg class="svg"><use xlink:href="/bitrix/templates/ranx-landing/assets/img/footer/social.svg?1675149157#zen"></use></svg></a>
    
</div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12 d-flex justify-content-center">
                
<div class="footer-payoptions">

        <div class="footer-payoption footer-payoption-cash" title="Наличные"><svg class="svg"><use xlink:href="/bitrix/templates/ranx-landing/assets/img/footer/payoptions.svg?1675149157#cash"></use></svg></div>
        <div class="footer-payoption footer-payoption-visa" title="Visa"><svg class="svg"><use xlink:href="/bitrix/templates/ranx-landing/assets/img/footer/payoptions.svg?1675149157#visa"></use></svg></div>
        <div class="footer-payoption footer-payoption-sberbank" title="Сбербанк"><svg class="svg"><use xlink:href="/bitrix/templates/ranx-landing/assets/img/footer/payoptions.svg?1675149157#sberbank"></use></svg></div>
        <div class="footer-payoption footer-payoption-maestro" title="Maestro"><svg class="svg"><use xlink:href="/bitrix/templates/ranx-landing/assets/img/footer/payoptions.svg?1675149157#maestro"></use></svg></div>
        <div class="footer-payoption footer-payoption-alfabank" title="Альфабанк"><svg class="svg"><use xlink:href="/bitrix/templates/ranx-landing/assets/img/footer/payoptions.svg?1675149157#alfabank"></use></svg></div>
        <div class="footer-payoption footer-payoption-mir" title="Мир"><svg class="svg"><use xlink:href="/bitrix/templates/ranx-landing/assets/img/footer/payoptions.svg?1675149157#mir"></use></svg></div>
    
</div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12 d-flex justify-content-center">
                <div class="footer-links d-flex flex-wrap justify-content-center">
                    
<div class="footer-copyright ">2026 © Все права защищены.</div>

                                    </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12 d-flex justify-content-center">
				Мы используем файлы <a target="_blank" href="politika-cookies-insortex.pdf">cookie</a> для улучшения работы сайта и сбора статистики. Продолжая использовать сайт, вы соглашаетесь с нашей Политикой конфиденциальности и обработкой персональных данных.
            </div>
        </div>

    </div>
</footer>-->
