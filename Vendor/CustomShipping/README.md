Задача 1
1. Добавить свой метод доставки FixShip с фиксированной оплатой. Проверить, учитывается ли стоимость по этому методу
при оформлении заказа. (можно не фиксированную оплату, а в зависимости от категории продукта)
2. Установить доступность этого метода только при заполнении поля Company (можно любого другого необязательного
поля).
3. Установить в админке возможность выбора стран, для которых фиксированная оплата уменьшается на указанный процент.
Также указать в админке список, компаний, работники которых не оплачивают данную доставку. Если срабатывают оба правила
“страна“ и “компания“, то оставить в приоритете “компанию“.

Задача 2 (реальный таск)
“Middle Name/Initial - Shipping“
Описание
При создании заказа в админке необходимо, чтобы поле Middle Name/Initial становилось обязательным, если выбран тип
доставки Free Shipping (при необходимости включить этот метод). При других типах доставки это поле не является обязательным.