<?php
/**
 * @rusifikator Simple Image Gallery Pro 3.0.8
 * @author JoomLang.Ru
 * @copyright(C) 2012 - 2015 - www.joomlang.ru
 * @license GNU/GPL v2
 **/
defined("_JEXEC") or die("Restricted access");
jimport('joomla.application.helper');
jimport('joomla.filesystem.file');
jimport('joomla.installer.installer');
@$app = JFactory::getApplication('administrator');
@$app->initialise();
define('JLANG_NAME','SIG Pro');
define('JLANG_NOMBER','a50067c65f639371adea09509da49a56');
define('JL_COMPLETE','data:audio/wav;base64,UklGRhI9AABXQVZFZm10IBIAAAABAAEAQB8AAEAfAAABAAgAAABkYXRhfCkAAH+AgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgYGBgYGAgIB/f35+fn+AgIGBgoKBgIB/f35+fn5/gICCg4SEhIKBgIB+fXx7e3x+gIGBgH57eHd3eHp8f4KIj5WampaQiIJ+e3t6eXd1dXZ4eXdwZ2BeYmx7iJWfpqutraqiloVzYlZRVF9ugJCcpKeloJeLe2hROyknMUppiaKyu8HGysnAqo1vVkhKVmZ0gIiQmaKnpJiGcVxKPzc0OEZbdY6ltcHIy8vCspyFcWJbWlxia3iGlZ+gmY6AdGdZSj41N0Vbd5GouMHGx8XAtKKLdGBUUllnd4SNjoyIh4WBeWdPNyszSmuHmqGjqbjM29rGo35iV1pjamxqbHOAjZWVjH1pUz8vLz1UboSWo7HE1+Tl1LSQcmBZWl1eYGdygI2XmJGCcmBQPjAyQVlziZmmtcnd5+DKqop0aWRgW1lbZnaEjIyIgXlxZVA4MDtOZnyLmKa5z+Lm2cGljXxtY11ZWWBueX1+gIOEgHFYPy45UmVzeYCQqMLX39bDrZyOgnlsX11lcXV0dnl8f351ZVdMR01cb35/hZKgsL3Bwbesn5SJgHl3c29vb2xrbnJ2cm9oZWNiYGZtcXV7goyUnKGosLSyq6CYk42Fe3JsZmBdXmNmam9wcXd3dHN4eXt7fYCIj5Wdpaurp56VkI2EfHhtaWRqZmludHR5eHl5end1eXt6fX5/hYuQkpmZnJGQj5OGhICGenRzcnNwcXB0eHt1e36AgIB+goSBfICBhYOGhYuKioWKjIyFgICAe3d4d3p2enl5eX6AgX+DgoCAfn+BgYF9fYCAgYWGiIWEhIOEhIGAfXt8f357fX5/gICDgYCAgICAgIF/fXx/gICAgoOBgIGGhIGDgX5+f39/fYB/fXt/g4F/g4F/e4CAgH+Agn58gYGCgYN/gICBgIGCgoCAgIGAgH+AfoCAgYCBgIB/gH9/gH9/fnqAg4GAgYJ/f4GAgICAgoB/gIGAgIKDgYCAgIB9gICAfn2AgICAgoKBgYCAgH99gIB/gICAgYGEg4GCgIF/gIB/f39/gICAgICBgYCBgIB+gIB+gIF/foCBgICCgYGAgICAgICAgIB/f4CAgIGBgICAgICAgYB/fn+AgICAgYCAgICAgICAgICAgICAgYCAgICAgIGAgICAgICAgICAgH+AgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgH+Af4CAgICAgICAgICBgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgH9/gICAgIGBgICAgICAgIB/fn6AgoSCh4N8e3+AfoB/fX1+goeMhoWAend8gIOEhH15e42Qgn9/eW1xen18fX6BgoOFiImEgH98eXZ5foCBgoCAgoKGh4eCf317foCCgoKAf36AgYOCgH9/gIKEg4GAf3+AgIB/fn6AhIeIhoOAenh7fn5+e3p6fn+AgH15dHJ0eH+Ch4uQl6CjoJiNfnBtdH6Hiod+dnN4gH5uWUc6L0d9n6mgqry6rqSioYhvZGl5e3t5d3+HlKSnnYVxa2tmVD4uFhhYlLCpqL/Buaufo5WAbWd4em5iWGV8k62zt6+Yg3uBf108HQkAIoC+0r21urKjlZWhloBnYHB8e2xeZWt+n8HOupuEcGpnZ1k5GhMVPpnZ6L2joZ6YiY6dkn9oZ3yEhHVmZmp+kKOzr6KPgXhrZl1NPjExL2Snxr6hnZuWkomUnJiKdnJ0dnVraGl0jKCsqaKhlYNtYmZaSjk5NEKAsMKrmZiXmZaYpaWdgWxmZWxram91hpeiqaesoYluWlxaUEQ+PDJfl7yzmZKUnqOgpKKdi3RqZG1ybWdne5Wnr6imn5F+amNfU0U4Ny9Rjbi3mZCSoailpaSfkXpsX2Nvc29mboSbqqqqo5aBbWVlXU09Ny49d6m7oJGMm66yr6mhlIB1aWRqbWlgZX2ZrKuoo52NeGlkX04+NzQ0XI2wp5eTmq62samhmol8cWhpa2liYW6Hn6alo6Kbi3xybV5JOTYvQWqPmJGUmqy9wLeqoZOCdmhiYWVjY2t8kJ+mpqSflIV5b2FRRUE/Q1x4hoeNlZ6ss7CpoZqSj4V9dnNva21xdXuAhIqOj4yMjId+c2tgU1JaWVJUYHCDlqSvuL3AwbqqmYp+cmpkXl5jaHF6gIOLko6FfHBiXl1WTUxTYXmRn6m0vcbLybijkIN5cWheWlpgZ3B2fIWMioR8cWNiZ2BUT1hnfpWjqayyusLCs56KgHt4dG1nZGZtc3V3fYGCf3lxZ15kaGBXWGN0jJ6kp6uxucG6qJeIgH19d25nZWlvc3N2e39+e3VsYV5nZ2FbXml+lqOpq6ywuL2zpJKDfHt7dGxnZ2txdXZ5fYB9eXBmW11mZF5bYW2Fn6qurK2xubuvnYl+eXp5c25pam51eHp+gIB8dmthWWBlYFpcZHSQpq2tra+2v7urlYF3dnp4cmplZm12e31/f3t4cmhbWWNhXFheaH+brLGwr7O8wriljHlvdHl3cGpma3V+gIKBfXhzaFlSXWBeWVphdZaut7avrbO+vK6Ue2xtd3t4bmdncHqBhYJ8dXBoXVZeYV5ZWWFzlK25ubSxtL+/s5h8aGZxeXhvZGFqeYSLiH5waWRdV19gWlVXYHWXsb/AuLGyu7uwmHxmYWp0eXVtaW15g42Lfm9kXFNYY19WUFVif6K4wr2zsLa+uqyQdGFgbHd7dWxobXqHkIt9bV9WUFxiXlRPVGWHqL3Hw7y5v8G5ooNmVVRea3Rzc3Z/iZWbloNtUT80OEhRX2N0jbPQ5u3kzrykjHJZQjY1QFRuhZiosbW0rZp+WzMQDCE+V3F/janQ8v//6MGefmRNOycgKkVsk7PEyMO7s6OHYTYJAAAfUnaSnKa93vr/+tKgeFpHOjAoKz9giK/J0Me7sKWPcUQWAAAJQXSZrbC60u///+CseVE5Ly8zOkxoiqzCycO7saSKaDkOAAAWUXycqKu30O//+daidVNBOjg4OUtmiqzDyMG4sKGHXzQFAAAoXoSip66/3vr/9MeOYUA2NDo7RFd2mbbFxb22p5FvRBYAABhSfZ2nqbTO7f/61ZxtSzw+QUNCUGmLrMDCuq+mlH9XKgAAC0Fylqanr8Xm///krXdQOzw/REBJXoCkvsS+saeVf1gqAAALP3CVp6m0ze3//+SrdEw4OT5DRk9lg6W8w76ypIxwRxoAABxSfZymqrjU8f/40ZhnRz1CSUtMVGmFpLe6tqydh2pBFwANM2SFl5mft9r7/+++h2FPTlJPSEVSb5Cqsa2mnZKAYjkTCyJPeJGYlqC73vn32Kd7YFVWVlJPU2N5jZuhpKOZhmhDIhwvU3SIjo+as9Lq7daviXFlYV5YVVhjcX6GjJOWlIdxTzQvQV95ioyKkaXB2N/RtZV9b2pmYmBgY2Zrcn2JkI+DbVFBRlpzg4mEgoyjwNTWxKmQgHl4c2thXV1ianV+hIeEe2xbUlVicn2BgIGMobbGyb+rmYqCfHVtZF5eYGdud3+Eh4J4al9eZXJ8f316gI2itL68sJ+Si4iCem9jXF5la3B1en6AgHx2b2prcnuAgH+Ah5SksrWtoJOLiIaAdmphXmJpb3V6enp8fX56dnR1d3l7foGHkJmho6Cbl5OPiYJ7cmpnbHFzcnN1eHt+f316eHl7fH1+gIOJj5OTko6QkpKPioF5dHJydnh3c3J0d3x9f4B/fHp8f4CBg4SFh4iIiYuKi4iHhYF+fX56eXx8eXl4d3h8foCAe3l9gIGGiYSAfoKIiIeIhYCAgoGAgIF9enh9f4KDgX18ent+gYF/ent+gYOGg4ODhIKAgYGAf4CBgoB/gH+AgH58fn99gIOAgH+AgYJ/foCAfX9/goSCgYGAf4KDgH58fX6AgYCAgoB9gIGDfH5+f319gYSDgYR/fX6EhYGBg312fYWFgoCAfnt/hIOEgYB9fXt6foKGgHp8fYCFh4iBfnp9f4CFhIB5fICBhouDf318f4F9fIN/en+AgYKCgIB/foCBfnx/f4CHhYKCf36Cg4GBgHt5fn19g4SDgIF+fH+EgoGBf355fYCEg4OCgH9/gYOEgYB+fICBgH+Af31+gYKAgIB/gYGBgH5+fn+BgoKEgn+AgYKBgICAfX1+f4CAgoGAgICAgYGAgH9+fX+AgYGBgICBgYCBgH99f4CAgICAf3+AgYGBgH5+f3+AgYGAf3+BgIGBgIB/gICAf4CAgICAgYCAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICBgICAgICAgICAgICAgICAgICAgICAgICAf4CAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIGBgH9+fn+AgIGBgICAgIGBgIB/fn1+f4CBgYKBgYGBgYCAgH98eXyBhIKHjYV5eomNfnB1f4B5eYCHhYB/hIeDfnx+gH99foCAf3+AgoF/e3t+gYGFioV6fJCPenB/hn13f4R9eX+EhIGAgYGChISCgoJ/eXl9fnl2fIODgYOHh4SCgoJ/e3l7fX19g4uKhIOJh395e3x5dnl+f3+BhoiGhIKBgH99fX+AgIKEhYKBgH99fHp4d3p8foGEhYF/fXt0bnB2eHiAjZaaoampoZaLgHVtaGhrbnF1f4iLh4B5b1pOYW9iYn6Vn6i0wcKtmJCHc1xVWlxbZ4GSlp6rsamYjIJzY1hQSEI+Ok15jYqXs7uzr7O1ooNxbWlfX2dxeH6FkJyhnp6bj35yaWhsal9aWVJIYYiMf4mhp6Slr7Ohg3Z2cmpnanR6fISTmpiXmJWLgnpwaWtzc2hfWVBEWH+FfIqkra2tt7ulgm5qZV1aZXJ7gYydp6Wem5ODdGxnZGVsdHFnYl9SW3yEeoCXpqyssbqsjHdwa2NcYG16goydqqifm5OFd2xoZWRrdXlya2dgVF95fXWAmKaqrLS2p413bWljXWFreYWPmqiropqRhXVoZGNkanJ3dXFvbGNldnx2doSXo6WpsKych3p3dGpiZG16hIuXp6mdkYuFem1mZWZobHJ1dHNwaWx3eXJyf4+anaWtrKKTiYR9b2NhaHF4gpCanZuVj4h/c2llZ2dqcnd7e3p1dXp4c3F3gIqTmZ+jo52WkIqAdWxrbnN5gIiOkY6KiYZ/dXFwb3Bzd3l5eHZzc3V1c3N3f4WMkpmen5qVj4qDfXd3eXt9gIOHh4aFg4B7d3V2dXd5e3t7eXd1c3R0c3V4fYKIjZKWlpWRjYmFgn99fn9/gIKEhYSDgX98enl5eXl5eXp6enl5d3Z2d3h5e36AhIiKjY+PjoyKiIeFhIKBgYGBgoKCgoB/fn18e3t6enp6ent7enl5eXl6e3x9fYCChIaJiouKiomJiIaEgoGBgICAgYCAgICAf39+fXx8fHt6eXp6enp7fHx9foCAgIGCgoOFhoeHh4iHhoaFg4OCgYB/gH+AgICAgH9+fn19e3t5e3p7e319fn1/f4B/gICAgIKDhIWGh4eGhoaFhIOCgYCAf4CAgH1/fXx7fX18e3t8fX1+gICAf4CAf4CAgIB/f4CCgoODg4OEhYWGhYSCg4KCgYB/fn18fH18fH18e3x+fn59f39/gICAgH+AgIB/gICAgIKDhYSFhoaGhYSDgoGAgICAf31/f317fHx7e3t8fHx8f3+BgICAgIB/gICAgICBgoSFhYeHh4eGhoaEg4KBgH5/fXx8eXl7eXp6fHt5fH1/fn9/gH5/f4CAgYODhYWGhomJiouJh4aEhIODgYB7eXh2eHd3dnh2eHh7e317enx9fX1/goaFiYyOjYyNjYmIiIeEgoSFgH+BgHx4d3d1cHR4end3eXdycXN2dnd4gIeKj5ieoZqVlI6Dfn+AfHp7fn+BgYaIhYB6dXNwc3d0cm5raGlucnV5e4ONlZymrK2lnJaLfndycHBuc3h+g4aNk4+IgXx2b3JyaWFgW1Jdb3N1fIubn6W1vLanm5iHc2pqZWJkcoCEipObnJaRjoJ3bGprYlZUUUZCY3t3fpSps627zL2lkol9YlhhY15me42Vl6SroZaQi3plZGNjYF1iXlFFUnl+d4egrKmpwsmvmY+EcVpbY19ic4aUmaGqpZ2XjIJtYGFeYmdhY15RQ1h8e3iMo7CprcvIp5WOfGJTXWFdbH6Om6KsqJuWkIBzZ2RhXmp0aWZkUz9VfXRxiqauprLWx6OVjXVZUGFjW3CHk52otKuZkYl2aWZmX2JyeG5tb1s9SHZpXn+gqaW23c6vp5R7YVJbXFRpfoeXqrevop6PdmtqZVhbb3NqanVrSD9xclVymaGirdLUtK6kg2peXFlPYXh8i6i1sqmomn9yc2ZaWWhsZWl6bVJEYHBZZo2Wm6e/2MKxs5t0ZmBdSlJrcXeWrbGuraiQfHlzXltgZ2NocXhmUUxtall5ko2drsLNvLyykHpyYVNMWmFkeJaiqbCxp5CFgm9dYVpfZGdqbmdbS2x2X3KPkJuqv83AurSZgHZkVU5UWmF1jZ+psK+rlIuGdmNkXF9haG1saWNRYnBhaICIkqO1xsS/uamPgG9fVFJWYGp9k6Kmqqecj4h7cGpjXGVqbnB0b2FWb25ea4CCi5uvv7q5t6iPhnpqXFtdYmh7jJSWn5qRjIZ/enNta252d3h5c2ZgZmxgZXJ7fo6er7S0sKyekIl8cmppaW10gIOGiYeEg4GBgH18en6CgHt5c2ZWW2VfYHCAhpGhsratpqKRfnhzbWlveYCHk5qZlY6Ffnd1dHNzc3R7gH11bmVWSVVqa22ClJulsLu7q5iOgG9lZ2xucoCPlZmen5iKfXdxa210ent6foeGe3BoWUU+VmppdZOkq7K8xLebi4FvW1pjam58kZ+gpqmhjoB0a2RlbHd9gICIkIh4bWFLNT9hZ2iGp6+zv83GpY6EcFNOW2RldI6gpKiwrZeAdWlbWWJveoGFjJWUhHNlTzQqSmRmeqS5vsbS1LqUgXNUQktaY3CJpbO1ubmmiHJlVUxTZXJ7hpGbmo99alE2IzZXX22ZusTK09rHm4J2WD09TF1qgaW6uru9qox0ZVZKTF5ve4aRnaGVgGtTNxwnU2Jrk7zIztbe0aaCc1k+PE1eboSju8PDwrOVd19RR0dXbn+KkZuimIVuVDoeETZfbIS20dXW2da2hGlZPjE/WW+CnLrHxMC2nXtdTEZFUGqAj5ieop2NdVc9KxcXSXSDocve4NXLwptsV0o7O1BtiZ60ys7Cs5+FaE1DRU1eeZSipKShkXpdRDMiEyNbgpW12ufeybutg1hIRT5CXH+XprnLx7OejHRVQ0NNWW6InqWioJmFblU+MCMbM26Tor7b4Na/sKF/W1BOTlpwiZ+rtb66qJSDcFlLTlpmeIybnJaTiHhpWEQ5MjNCapCltMDGxbeglYZvWlVbaHSClaSoq6milol5a2FdYGl2g42PjYmAc2pjWU1ISU5VdJSgqbC3tKeXl5GAcm9xdHh/jZSVl5mYkYiCfnRubm5xdnl7eXp7dnFvbmtjYWZpbHyJjpWWmKCclpSTioN9fYCAfoCEiYqKjI6LgIB5f3lzdHZ1bXZ1fnl1eHp4dHR9dXx2f4GEiomNjIyMh4mFhIN8gYCEg4GHgoiChYOFeX18fHl7e3l3fn18f3+Afnt+hHuAf4B/gYR+h4CHgIGAgoGAf4OCf4CFg4F9iYF/fIGCfnmBgH56gYCBfIGDfYR+g32Af4R/gXyEgH2DfIh9f4F/g3+AgoCBfICAg3iDgIB9gX+CeoR+f4J6hHmHfICCfoh1jnWLdod9gH6AgYF9gn6Df4OAhX2Af4SAeIh6hniGeYd8hXyGeYV6g4B/gHyFfH+Df4Z6gYCEeoN9h3l+hX2EeoSCgIJ9hXyEe4V6inmAgICFeIaAhHqCf4OAfX+Cg3h/g4F+foJ/g3iIf3uGeoh5f4h3j2+QdId8hX2BgIKBd4d7iXqAgYCBfYKDfYF9gn6BgIB/f4Z6hHqIeod1i3iHdYx4iXaHfIV6h3mFfYN+fYR9hHeJeoN9g32Feod3iH6Be4ODe4R6ineEgHyHfYCBfYV8gYF+g3yCfoGAfYV5iXaJeIV5iHuAgX2CgHmMdopxjneKd4GAgYB9hnyDeoSBgIB9hH+AgH6CgIJ8gn+DgIB+hnuDd4WBe4R8h3t+iXSLeIh+fX6KdYd6hYB/gICCeoh5iHaKdYl5inKPdIp7gIdymGSWcI93f4V9g3mFgICBfIV9g3+AgHmIe4J7gIh2h3uGgHyEgH+BfoZ2gIGCfX+HfYN2jXiGe4Z4e4CFe4V8iXmGeYp4inGLfXeMcot7hYB7iHyDfn6FeYOAgH+AhHuCg3qKd4SAfYN4i3mChHSQbZhmlnSFfXuPbJpklXaEgHaSaZxhnmyHhmqgYp1foGuHfXuPb4t8g35/im6Xbo5ui35/gHmOfH1+hIh0hnyHf3qAgIl0h3yLeneKfIl1g4N8fIGJcpFvkHGOcomAfYR6g3+BfoR4jHeGe4R8gIJ+g4B6ineKd4OEe4V7hXp9jnOPcZFwjHeFgHuHeYd5goKAgnuEfYGBeod8gYB/hH+Agn2IdI50hH+AgXx/goN7hIGAgHqCgn2Afod7gHyGhH6BfYZ8g3eGgHmDe4x2iHiLe4J7hYF4gIGAgYB/hH2FgH2AiXeBfYR9gHyHfIKAfY1vk2uQdoOBdZByiXeLcZNxhX+FgHqGeoxyjHSSbIt4i3GMf4WDdIxykW2NeoB5hIB6hoSHen+HeYVzi39/dn+NeYGDiXx9hH+AfIB+foB6ioCCdo98hn+AgnWAd4h7fI98g4CJgICCeIdwgXl9gIF/hJB8hICCgHt8gHt5f4CGgId9jXiHgIB3e4N8gH6Ke4N5kHyNeIl4f4FyhnyKeYWDjHN8h4V8g3+Fc313inuDgX6Dd4Z7iYGBfXyFfX58hIR2gH2IfYWAjX6DfX9/e36Bf3uAg3+BgoaAhX9/gH+Ad4J8gX6AgICHfoOEhX55foJ+eHyIhX6Bg4eBfoGFgXd0e4GIgIOCh3mAgIl9gHqAfYB/gYSCf3+Af39+f32Aen97gICAf4F8f31+f4F/hYCBgoKDhoaJiIyKi4qKioaCgIB+fXl2c3Z3eHh1cnB0cW5pZmljc4SYnJiWlZKKjJKWlI6LiouOjYyKhXx1bXJ7gIiJhHZqaW5rZVtWUUlJb5KioqCpqZCAh5aWhoORlY6HhoqGfHx7dnRvdIWWoaOekHdgWWZqX01GQTtEfqm5rp6gopCIj5OQfXiCjpGNg4F/dXJ2gIiKioiIkpaVkpCBaFBSX2JdWFdRQ1iVsbKinKKWf4GKlZaIiIyKhHp8goR8cnB6gIyYnJWKhYeHjod0XU9SYGBbWVNHV46ssKGZmZKEh5GfmoaAhYuIe3l+fHd3fomPkJKQj4mChYiIfWthXmBnZ2FcVExWhqSilpCXmpCQkZibiICGjIh3b3V7gIKEi46PjYqKiYKDg4SAdWxhYWVlYV1VT1F5m6CXk52jmZKTmpmJgISIgXVtbXeAgYOMk5OQk5GHfnt9gIB6c2xmZGdmY15bUVl6k5aNkZ6hmZiZn5qKgICAfXNtbXV7foCQnp6VlJWOg3p4fnx0bXBxbWptbWxkWFRjfIKBh5SanaCio6CYi4CAf3pyb3B3e36DjJSUk5KPi4aBfXh1cnFxcXJ3eXdybmlgXmhuam96g42apKmqqaOblI6IgHd1eHx8fYGIjIuKiYR/end3eXl3eXt8fHt2b2ZdVFRaXGNueoiZp6+1t7GooJeOhHx3dXV2eX+ChIaFhoOAfHp7fH1+gIB+fHx5cmlhWFJWWlpjc4CNm6qzt7exqaCVi4F6dHFydXh7gISEg4KBgH18fX1+f4CAf358enNrZV5UU1xeYGx7iJelr7W6t62lm5KHfnVvb3J0eX6ChYaHhoWCgH18e3p7e3x7eXl4cmtmXlVUXWBibXyKl6Wvtbm3rqWdkod9dnBvcXR4fYCEh4iGhIJ/fXt6eXp7enl3d3h1bmlkXFZZZGZodIKOmaausrWyqqKZkIZ+dnFxcnR4foOFh4uKhoOAfnx7enl6enh4eXp4c21nYFhXYWVlb32LlqOssbW0q6Ockol/dnFwcXR4fICDhYeGhYSBf3x4eHp6ent6eXh6eXNsZ2JbWmJpanJ/i5agqK6xsKmhmpGIgHhzcXJ0d3t/gYSGhoSDgH9+fHt7fHx8e3l5eXhzbWlkXVphaWpwe4WQm6Sqrq+qo52VjoV+eHRzdXh7fH6Bg4SEg4KBgH9+fn18e3x8enl4d3JtamhjXmNsa296hY+XoKWpq6mjnZWOhoB5dHNzdHV2eX+AgIKGhoWEg4OCgH5+fXl2dXV2dHBramhkZG1ycHV/iZKco6irrKmjmpGJgXpyb3Bxc3Z5f4KEh4yOjYmAd3Jydnl7eG9kX2h1eXRtZ19acZanno+NkI+RlqClnY+CfHhxaGBjdYeTmZ2eloqDhYiGgXlwZ2Noc4GMj4h9cWpwgIuDb11RRlKDq62RgIKDg4uft7mmjHdtaGVnbnyFh4WGjpKQjY2PjYeAeG9lYml3hYyMhHlqY3CJmItuVUM5RYG6v5lyaXSCmrXMyKN7X1lgaHN5fX9/fX6LnqyolYeBgHhxcXJuaWt6iY2Fe3VydYKPim9UQ0BNiMnLlmFXboahvtHJoG9TVmp8hoV8dHF0fY+kq6GQhYaGfnJsbGpnbHqFiIOAfXh3fIaId11MSElencm4g1xkgpuzxcSuhF9OWXOHiHpwcXuAjZ6pp5aIg394dHN1cGdlcH+MjYWAeXV6hI2AYkpBSFiGwseXYVFulLLEwbCRbVpecoWLgG5mcICLk52inpOFfYCGf25lZmxyeIGKjouBeHV4gIV/aVJGSE51t82mblVpkrLFwa2QbldWaIKTindnanyKl6OkoZaIeXF3e3t1a2ZrdYCLk5SIe3BvdHt9cFpHRU9wrcuygF1jhqm9uqiVemZeZXuMjn9qZHOHmaGknJCFfnuCiIFvX11peYOMkI+Lg3dwcHZ9em1ZS0pRb6jItYVjaIinvLiii3VkX2R4iIyAcmx4iJigpKCRg3p5fX96cGZnbHWBi5OSiYB2cXJ4gH1oT0ZOXHy0yqx7X2yOr8CzmYBsY2FrgIuLf3N1gY6WlZGSkIeAfnl0c3d5dnJxeIOQkYh+dW9vdYCAcVlKTFhypce1iWhmgKG4t6KJcGFgan2Mkod0bXmJlp2ck4eAfICDgXtzcXFydnuBhomJhYB6c25xdXVsYFhWXYCuuJ99cH2Spa+nlIBxaWlyf4iGfHZ8h5GVk5GOi4Z/fH59d25sdH6CgoGCgYKFhH9za2dpcHFqY19jeJqvpot5e4qZop+Xi3pvbHF8hIN/fYGJjo2Mjo+LhoN/dnBxdnt9f39/fX2AhomHgHNpY2dyfXhsY2RvgZilnY2DgoiQl5iRhXlydHl/g4OAgIOKj5COiYF9f4CAf3x3cXJ7g4eHhYB8eXuAgX51bGdobnV4d3d6e4CLl5iPiIWFh4qNi4eCf31/gYWHiIaFhYaGhYWDgH17fX59e3t8fX5/f317ent6eHVycnN0c3JzdnqAipCOioeHioyPkI6Jgn18foGEhYOCgYOFh4eHhIB+fHt8fn58fH1/gH9/fXx7fH18eHVzcnN1d3p8fn+Ch4mJiIeIiYqJiIaEgYCBg4WEgoGBgYOFhYOBgICAgH9/fn59fn9/f399fHt8fX18e3p4d3h5ent8fX1/gYKCg4WGhoaHh4aGhYWFhYSEg4KCgoKBgYGBgYCAgICAgICAf39+fXx8e3p5eXl5eXl5eXp7e3x+f4CAgIKDhYeHiIiJiIiIh4aFhIOBgICAgICAgICAgICAgICAgH9+fXt7enx+fn19fX19fX5+fn5+f4CAgYGCgoODgoKCgoKBgYGBgYGBgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgA==');

global $myFiles;
$myFiles = array();
$myComment = '; @author      JoomLang
; @package     '. JLANG_NAME .'
; @version     JOOMLANG_VER_COM
; @copyright   (c) 2006 - 2015 JoomlaWorks Ltd. All rights reserved.
; @copyright   (C) Translation 2012 - 2015 JoomLang - http://www.joomlang.ru
; @license     GNU/GPL v2
; @data        http://'. $_SERVER['HTTP_HOST'] .' | Generated: '. date("d-m-Y H:i:s") .'
; Note : All ini files need to be saved as UTF-8 - No BOM';

$myFiles[JPATH_ROOT .'/language/ru-RU/ru-RU.files_'.JLANG_NOMBER.'.sys.ini'] = $myComment .'

'. strtoupper(JLANG_NOMBER) .'="Русификатор '. JLANG_NAME .'"
';

$myFiles[JPATH_ADMINISTRATOR .'/language/ru-RU/ru-RU.com_sigpro.ini'] = $myComment .'

COM_CONFIG_COM_SIGPRO_SYSTEM_SETTINGS_FIELDSET_LABEL="Системные настройки"
COM_SIGPRO="Простая Галерея Pro"
COM_SIGPRO_ABOUT="О компоненте"
COM_SIGPRO_ABOUT_TEXT="Добавлять галереи изображений в ваши статьи Joomla теперь легко и просто, используя волшебный плагин \'Простая галерея Pro\' для Joomla!.<br /><br />Плагин может превратить любую папку с картинками, расположенную внутри вашего сайта Joomla! в галерею изображений в сеточном стиле и отличным предпросмотром в lightbox. И всё это с помощью простого тега плагина <b>{gallery}myphotos{/gallery}</b>.<br /><br />Чтобы узнать больше, посетите следующие ресурсы:<ul><li><a target=\'_blank\' href=\'http://www.joomlaworks.net/simple-image-gallery-pro\'>Страница Простой галереи Pro </a></li><li><a target=\'_blank\' href=\'http://www.joomlaworks.net/docs/simple-image-gallery-pro\'>Документация Простой галереи Pro</a></li><li><a target=\'_blank\' title=\'Посетите JoomlaWorks!\' href=\'http://www.joomlaworks.net/\'>Подробнее обо всех расширениях JoomlaWorks для Joomla! и других систем</a></li></ul><b>Простая галерея Pro</b> это <a target=\'_blank\' href=\'http://www.joomla.org/\'>Joomla!</a> расширение, разработанное <a target=\'_blank\' title=\'Посетите JoomlaWorks!\' href=\'http://www.joomlaworks.net\'>JoomlaWorks</a>.<br /><br />Авторское право &copy; 2006 - 2015 JoomlaWorks Ltd.<br /><br /><i>(Последнее обновление: 22 Июня, 2015 - Версия 3.0.8)</i>"
COM_SIGPRO_ADD_A_GALLERY="Добавить галерею"
COM_SIGPRO_ADD_FILES="Добавить картинку"
COM_SIGPRO_ADD_GALLERY="Добавить галерею"
COM_SIGPRO_ADD_IMAGES="Добавить картинки"
COM_SIGPRO_ADD_NEW="Новая галерея"
COM_SIGPRO_ADJUST_THE_MAXIMUM_IMAGE_THUMBNAIL_HEIGHT_IN_PIXELS="Установите максимальную высоту эскиза в пикселях"
COM_SIGPRO_ADJUST_THE_MAXIMUM_IMAGE_THUMBNAIL_WIDTH_IN_PIXELS="Установите максимальную ширину эскиза в пикселях"
COM_SIGPRO_ADJUST_THE_QUALITY_OF_THE_GENERATED_IMAGE_THUMBNAIL_VALUES_RANGE_FROM_0_TO_100_WITH_100_GIVING_THE_BEST_POSSIBLE_RESULT_VALUES_BETWEEN_70_TO_80_SHOULD_GENERALLY_BE_PREFERRED_REMEMBER_THAT_THE_LOWER_THIS_VALUE_IS_THE_SMALLER_THE_SIZE_OF_THE_GENERATED_THUMBNAIL_WILL_BE="Установите качество генерируемых эскизов изображений. Диапазон значений от 0 до 100, 100 дает наилучший результат. Значения от 70 до 80 обычно предпочтительнее. Помните, чем ниже это значение, тем меньше размер генерируемых миниатюр."
COM_SIGPRO_ADVANCED_SETTINGS="Расширенное"
COM_SIGPRO_ALPHABETICAL_ORDER="Алфавитный порядок"
COM_SIGPRO_ALPHABETICAL_ORDER_CASE_INSENSITIVE="Алфавитный порядок (регистр не учитывается)"
COM_SIGPRO_BASIC_SETTINGS="Основное"
COM_SIGPRO_BSD="BSD"
COM_SIGPRO_CAPTIONS_MODE="Режим подписей"
COM_SIGPRO_CAPTION_WORD_LIMIT="Лимит слов подписи"
COM_SIGPRO_CHANGE_YOUR_SERVERS_MEMORY_LIMIT_IF_GALLERIES_FAIL_TO_CREATE_OR_YOU_GET_THE_WHITE_PAGE_ERROR_ON_YOUR_SITE_THIS_OPTION_MAY_NOT_WORK_IN_ALL_CASES="Измените лимит памяти вашего сервера, если галерея не создаётся или вы получаете ошибку на сайте - «белые страницы». Опция может не работать во всех случаях."
COM_SIGPRO_CHECK="Проверить"
COM_SIGPRO_CHECKING="Проверяется..."
COM_SIGPRO_CHOOSE_A_TEMPLATE_TO_DISPLAY_YOUR_IMAGE_GALLERIES="Выберите шаблон для показа галерей изображений."
COM_SIGPRO_CHOOSE_SINGLE_THUMBNAIL_MODE_TO_LAUNCH_POPUP_SLIDESHOWS_FROM_THE_FIRST_THUMBNAIL_OF_YOUR_GALLERY_USE_THIS_OPTION_WHEN_YOU_WANT_TO_SAVE_SOME_SPACE_IN_YOUR_DOCUMENT_IF_YOU_SELECT_NORMAL_ALL_CORRESPONDING_THUMBNAILS_WILL_DISPLAY="Выберите режим «Одного эскиза», чтобы запустить всплывающее окно слайд-шоу из первых эскизов вашей галереи. Используйте эту опцию, если хотите сэкономить место в вашем документе. Если выбрать «Обычный», будут показаны все соответствующие эскизы."
COM_SIGPRO_CLICK_ON_THE_ADD_GALLERY_BUTTON_TO_UPLOAD_SOME_GALLERIES="Для добавления галереи, нажмите на кнопку «Новая галерея»."
COM_SIGPRO_CLICK_ON_THE_ADD_IMAGES_BUTTON_TO_UPLOAD_SOME_IMAGES="Для загрузки нескольких картинок, нажмите на кнопку «Добавить картинки»."
COM_SIGPRO_CLIENT="Клиент"
COM_SIGPRO_CLOSE="Закрыть"
COM_SIGPRO_COMPONENT="Компонент"
COM_SIGPRO_CONFIGURATION="Параметры Простой Галереи Pro"
COM_SIGPRO_COULD_NOT_UPLOAD_FILE="Невозможно загрузить файл"
COM_SIGPRO_CREDITS="Создатели"
COM_SIGPRO_DEBUG_MODE="Режим отладки"
COM_SIGPRO_DEFAULT="По умолчанию"
COM_SIGPRO_DEFAULT_CAPTION_LABEL_HANDLING="Подпись обслуживания по умолчанию (ярлык)"
COM_SIGPRO_DELETE="Удалить"
COM_SIGPRO_DELETE_FAILED="Неудачное удаление"
COM_SIGPRO_DELETE_WARNING="Вы собираетесь окончательно удалить этот файл с сервера. Вы уверены?"
COM_SIGPRO_DEPENDING_ON_WHAT_JAVASCRIPT_LIBRARYFRAMEWORK_YOU_USE_IN_YOUR_SITE_CHOOSE_AN_APPROPRIATE_POPUP_ENGINE_TO_ACHIEVE_MAXIMUM_COMPATIBILITY_AND_AVOID_ANY_JAVASCRIPT_CONFLICTS_POPUP_ENGINES_NOT_BASED_ON_JQUERY_OR_MOOTOOLS_WILL_GENERALLY_BE_NEUTRAL_ON_ALL_JOOMLA_WEBSITES="В зависимости от того, какую JavaScript библиотеку/фреймворк вы используете на сайте, выберите соответствующее всплывающее окно с целью обеспечения максимальной совместимости и избежания конфликтов JavaScript. Всплывающие окна не основаны на JQuery или Mootools и будут нейтральными на всех Joomla! веб-сайтах."
COM_SIGPRO_DESCRIPTION="Описание"
COM_SIGPRO_DIRECTORY_PERMISSIONS="Права доступа"
COM_SIGPRO_DISABLED="Отключено"
COM_SIGPRO_DISABLED="Отключено"
COM_SIGPRO_DISPLAY="Отображение"
COM_SIGPRO_DISPLAY_MODE="Режим отображения"
COM_SIGPRO_DISPLAY_MODE="Режим отображения"
COM_SIGPRO_DOCUMENTATION="Документация"
COM_SIGPRO_EDITING_GALLERY="Редактирование галереи: %s"
COM_SIGPRO_EDITING_GALLERY_OF_K2_ITEM="Редактирование галереи элемента К2: %s"
COM_SIGPRO_EDIT_GALLERY="Редактировать галерею"
;COM_SIGPRO_ENABLED="Включено"
COM_SIGPRO_ENABLED="Включено"
COM_SIGPRO_ENABLE_ORIGINAL_IMAGE_DOWNLOAD_LINK_IN_THE_POPUP="Включить ссылку загрузки оригинальной картинки во всплывающем окне"
COM_SIGPRO_ENABLE_THIS_OPTION_TO_DISPLAY_POSSIBLE_ERRORS_OR_OTHER_WARNINGS_USEFUL_FOR_DEBUGGING="Включите этот параметр для отображения возможных ошибок или других предупреждений. Полезно для отладки."
COM_SIGPRO_END="Конец"
COM_SIGPRO_ERROR_CREATING_THE_FOLDER="Ошибка создания папки"
COM_SIGPRO_EXTENSION="Расширение"
COM_SIGPRO_FILE_DELETED="Файл удалён"
COM_SIGPRO_FILE_DOES_NOT_EXIST="Файл не существует"
COM_SIGPRO_FILE_IS_NOT_AN_IMAGE="Файл не является изображением"
COM_SIGPRO_FILE_MANAGER="Менеджер файлов"
COM_SIGPRO_FILE_NAME="Название файла"
COM_SIGPRO_FILE_SIZE="Размер файла"
COM_SIGPRO_FILE_WAS_NOT_UPLOADED="Файл не был загружен"
COM_SIGPRO_FLICKR_API_KEY="Flickr ключ API (обязательно)"
COM_SIGPRO_FLICKR_API_KEY_DESC="Наборы (фотосеты) Flickr требуют ключ API для встраивания возможности использования Flickr API, с октября 2011. Войдите в ваш аккаунт Flickr и перейдите на http://www.flickr.com/services/apps/create/ для выбора создания некоммерческого ключа. Название приложения, это ваш вебсайт."
COM_SIGPRO_FLICKR_SETTINGS="Flickr API"
COM_SIGPRO_FLICKR_TOTALIMAGES="Всего картинок"
COM_SIGPRO_FLICKR_TOTALIMAGES_DESC="Максимальное количество картинок, для выборки из комплекта Flickr, упорядоченные по наборам на Flickr."
COM_SIGPRO_FOLDER="Папка"
COM_SIGPRO_FOLDERS_DELETED="Папки удалены"
COM_SIGPRO_FOLDER_ALREADY_EXISTS="Папка уже существует"
COM_SIGPRO_FOLDER_DOES_NOT_EXIST="Папка не существует"
COM_SIGPRO_FOLDER_NAME="Название папки"
COM_SIGPRO_FOLDER_NAME_ASC="Название папки (Восх)"
COM_SIGPRO_FOLDER_NAME_DESC="Название папки (Нисх)"
COM_SIGPRO_GALLERIES="Галереи"
COM_SIGPRO_GALLERY_CREATED="Галерея создана"
COM_SIGPRO_GALLERY_SAVED="Галерея сохранена"
COM_SIGPRO_GALLERY_SETTINGS="Настройки галереи"
COM_SIGPRO_GD_IMAGE_LIBRARY="Библиотека GD image"
COM_SIGPRO_GPL="GNU/GPL"
COM_SIGPRO_GROUP="Группа"
COM_SIGPRO_IF_SET_TO_DISABLED_IMAGE_THUMBNAILS_ARE_DISPLAYED_BASED_ON_THE_ASPECT_RATIO_OF_THE_ORIGINAL_IMAGE_IF_SET_TO_ENABLED_IMAGES_ARE_RESIZED_TO_FIT_THE_THUMBNAIL_VIEWPORT_AS_SET_ON_THE_DIMENSION_OPTIONS_ABOVE_THE_LAST_OPTION_IS_IDEAL_WHEN_YOU_WANT_TO_DISPLAY_IMAGES_OF_DIFFERENT_ASPECT_RATIO_IN_THUMBNAILS_OF_THE_SAME_DIMENSIONS="Если значение \'отключено\', эскизы изображений отображаются на основании соотношения сторон исходного изображения. Если значение \'включено\', изображения изменяется в соответствии с экраном миниатюры, как установлено в варианте изменения выше. Последний вариант идеально подходит, если вы хотите показывать картинки различной пропорции в эскизах тех же размеров."
COM_SIGPRO_IMAGES="Картинки"
COM_SIGPRO_IMAGES_DELETED="Картинки удалены"
COM_SIGPRO_IMAGE_DIMENSIONS="Размеры картинки"
COM_SIGPRO_INFO="Данные"
COM_SIGPRO_INFORMATION="Данные"
COM_SIGPRO_INSERT="Вставить"
COM_SIGPRO_INSTALLATION_STATUS="Состояние установки"
COM_SIGPRO_INSTALLED="Установлено"
COM_SIGPRO_INVALID_FOLDER_NAME="Неверное имя папки"
COM_SIGPRO_ITEM="элемент"
COM_SIGPRO_ITEMS="элементы"
COM_SIGPRO_JQUERY_DO_NOT_LOAD_THE_JQUERY_LIBRARY="Не загружать jQuery"
COM_SIGPRO_JQUERY_LIBRARY_HANDLING="Обработка библиотеки jQuery"
COM_SIGPRO_JQUERY_LIBRARY_HANDLING_DESC="Некоторые движки всплывающих окон требуют для работы jQuery JavaScript библиотеку. Вы можете выбрать, если хотите загрузить jQuery из локального файла на вашем сервере, с сети доставки содержимого (CDN) или не хотите загружать совсем. Например, ваш шаблон уже использует её. Внимание. Этот параметр полностью игнорируется в Joomla 3.x или более поздней версии, jQuery установлен в этих версиях Joomla по умолчанию."
COM_SIGPRO_JQUERY_LOAD_FROM_CDNJS="Загрузить jQuery с CloudFlare\'s CDN"
COM_SIGPRO_JQUERY_LOAD_FROM_GOOGLECDN="Загрузить jQuery с Google\'s CDN"
COM_SIGPRO_JQUERY_LOAD_FROM_JQUERYCDN="Загрузить jQuery с jQuery\'s CDN"
COM_SIGPRO_JQUERY_LOAD_FROM_JSDELIVR="Загрузить jQuery с jsDelivr\'s CDN (\'Great Firewall of China\' friendly)"
COM_SIGPRO_JQUERY_LOAD_FROM_MSCDN="Загрузить jQuery с Microsoft\'s CDN"
COM_SIGPRO_JQUERY_LOAD_FROM_QIHOO360="Загрузить jQuery с Qihoo\'s 360 CDN (\'Great Firewall of China\' friendly)"
COM_SIGPRO_JQUERY_LOAD_FROM_YANDEXCDN="Загрузить jQuery с Yandex\'s CDN"
COM_SIGPRO_JQUERY_LOAD_LOCAL="Загрузить jQuery с моего сервера"
COM_SIGPRO_JQUERY_LOAD_V1_10="1.10"
COM_SIGPRO_JQUERY_LOAD_V1_11="1.11"
COM_SIGPRO_JQUERY_LOAD_V1_7="1.7"
COM_SIGPRO_JQUERY_LOAD_V1_8="1.8"
COM_SIGPRO_JQUERY_LOAD_V1_9="1.9"
COM_SIGPRO_JQUERY_RELEASE="Версия jQuery"
COM_SIGPRO_JQUERY_RELEASE_DESC="Выберите версию jQuery для загрузки. Этот параметр полностью игнорируется в Joomla 3.x или более поздней версии, jQuery установлен в этих версиях Joomla по умолчанию."
COM_SIGPRO_JS_LIB="Библиотека JavaScript"
COM_SIGPRO_K2_GALLERIES="Галереи K2"
COM_SIGPRO_LABELS_LANGUAGE="Редактирование метки для языка"
COM_SIGPRO_LANDSCAPE="Ландшафт"
COM_SIGPRO_LAYOUT_TEMPLATE_FOR_IMAGE_GALLERIES="Макет шаблона для галерей."
COM_SIGPRO_LICENSE="Лицензия"
COM_SIGPRO_LOAD_A_MODULE_POSITION_IN_THE_POPUP="Загрузить модульную позицию в всплывающем окне"
COM_SIGPRO_MAX_UPLOAD_SIZE="Макс. загружаемый размер"
COM_SIGPRO_MEDIA_MANAGER="Медиаменеджер"
COM_SIGPRO_MEDIA_MANAGER_INFO="Вы можете использовать это приложение, чтобы реорганизовать свои папки галереи"
COM_SIGPRO_MEMORY_LIMIT="Лимит памяти"
COM_SIGPRO_MEMORY_LIMIT_ENFORCE_IN_MB="Выполнение лимита памяти нового сайта (в MB)"
COM_SIGPRO_MEMORY_LIMIT_IN_MBS="Лимит памяти (в MB)"
COM_SIGPRO_MIT="MIT"
COM_SIGPRO_MODIFIED_DATE="Дата изменения"
COM_SIGPRO_MODIFIED_DATE_ASC="Дата изменения (Восх)"
COM_SIGPRO_MODIFIED_DATE_DESC="Дата изменения (Нисх)"
COM_SIGPRO_MODULE="Модуль"
COM_SIGPRO_NEXT="Дальше"
COM_SIGPRO_NO="Нет"
;COM_SIGPRO_NORMAL="Обычно"
COM_SIGPRO_NORMAL="Обычно"
COM_SIGPRO_NOT_INSTALLED="Не установлено"
COM_SIGPRO_NOT_REMOVED="Не удалено"
COM_SIGPRO_NOT_WRITABLE="Не для записи"
;COM_SIGPRO_NO_CAPTIONS="Подписей нет"
COM_SIGPRO_NO_CAPTIONS="Подписей нет"
COM_SIGPRO_NO_ROWS_SELECTED="Выбранных строк нет"
COM_SIGPRO_N_A="Б/О"
COM_SIGPRO_OF="из"
COM_SIGPRO_ORDERING="Порядок"
COM_SIGPRO_ORDER_GALLERIES_BY="Упорядочить галереи по"
COM_SIGPRO_ORDER_OF_APPEARANCE_FOR_THE_IMAGES_PREFER_THE_DEFAULT_OPTION_IF_OTHERS_DONT_WORK_AS_EXPECTED="Порядок появления для изображений. Предпочтителен параметр \'По умолчанию\' если другие не работают."
COM_SIGPRO_PAGE="Страница"
COM_SIGPRO_PATH="Путь"
COM_SIGPRO_PE_COLORBOX="ColorBox (на jQuery/возможность слайдшоу)"
COM_SIGPRO_PE_FANCYBOX="FancyBox (на jQuery/возможность слайдшоу/responsive)"
COM_SIGPRO_PE_FRESCO="Fresco (на jQuery/responsive)"
COM_SIGPRO_PE_HIGHSLIDE="HighSlide (возможность слайдшоу)"
COM_SIGPRO_PE_LIGHTVIEW="LightView (на jQuery/возможность слайдшоу)"
COM_SIGPRO_PE_LYTEBOX="LyteBox (возможность слайдшоу)"
COM_SIGPRO_PE_MAGNIFICPOPUP="Magnific Popup (на jQuery/responsive)"
COM_SIGPRO_PE_NIVOLIGHTBOX="Nivo Lightbox (на jQuery/responsive)"
COM_SIGPRO_PE_PRETTYPHOTO="PrettyPhoto (на jQuery/возможность слайдшоу)"
COM_SIGPRO_PE_SIMPLEBOX="SimpleBox"
COM_SIGPRO_PE_SLIMBOX="Slimbox 2 (на jQuery)"
COM_SIGPRO_PE_SWIPEBOX="Swipebox (на jQuery/responsive)"
COM_SIGPRO_PHP_VERSION="Версия PHP"
COM_SIGPRO_PLEASE_NOTE_THAT_WHEN_YOU_SELECT_READ_CONTENTS_OF_CAPTION_FILES_THE_RELATED_LABEL_FILES_WILL_BE_AUTOMATICALLY_CREATED_FOR_YOU="Обратите внимание, при выборе \'Читать содержимое подписи файлов\' связанный ярлык файлов будет автоматически создан для вас."
COM_SIGPRO_PLUGIN="Плагин"
COM_SIGPRO_PLUGINS="Плагины"
COM_SIGPRO_POPUP_ENGINE_FOR_IMAGE_GALLERIES="Двигатель всплывающих окон галерей"
COM_SIGPRO_PORTRAIT="Портрет"
COM_SIGPRO_PREVIEW="Превью"
COM_SIGPRO_PREVIOUS="Предыдущий"
COM_SIGPRO_PROCEED="Продолжить"
COM_SIGPRO_PROVIDER="Поставщик"
COM_SIGPRO_QUICK_HOWTO="Начало работы"
COM_SIGPRO_QUICK_HOWTO_TEXT="Это краткое руководство поможет вам начать работу с Простой галереей Pro.<br /><br /><b>Шаг 1</b><br />Нажмите на значок шестеренки в верхней части боковой панели, чтобы настроить основные параметры галереи изображений. Например размер эскиза по умолчанию, двигатель всплывающих окон, макет галереи и т.д.<br /><br /><b>Шаг 2</b><br />Есть 3 способа загрузки галереи изображений перед подключением их к материалам К2, статьям Joomla, товарам Virtuemart/redShop и т.д. Вы можете загрузить папки изображений с помощью FTP (в корневую папку галереи, указанную в настройках компонента), перетаскивать изображения в Медиаменеджере или нажав кнопку редактора \'Простой галереи Pro\' при изменении любого пункта K2, статьи Joomla, товара Virtuemart/redShop и т.д. (которая по существу запускает опцию \'Новая Галерея\').<br /><br /><b>Шаг 3</b><br />Существует простой способ прикрепить галерею изображений любому пункту K2, статье Joomla или другому компоненту, который использует WYSIWYG редактор Joomla. Где есть визуальный редактор, вы увидите кнопку редактора \'Простой галереи Pro\'. Если вы уже загрузили папки изображений, используйте кнопку редактора \'Простой галереи Pro\', чтобы найти вашу папку галереи и вставить её в содержание. Если Вы еще не загрузили ни одной папки изображения, просто нажмите \'Новая Галерея\' после нажатия кнопки редактора \'Простой галереи Pro\'.<br /><br /><b>Шаг 4</b><br />При добавлении новой галереи или редактировании существующей, вы можете добавить текст названия и описания для каждого изображения. Если вы работаете с многоязычным сайтом, то можете перевести подписи к картинкам для других языков, выбрав правильный язык из выпадающего расположения в правом верхнем углу формы редактирования галереи.<br /><br /><b>Шаг 5</b><br />Для К2. Убедитесь, что у вас версия 2.6.6, в которой есть кнопка \'новая галерея\' (на вкладке галерея изображений) для быстрой вставки изображений или редактирования существующих. Также можно перенести галереи изображений K2, что раньше было ручным и утомительным процессом. Кнопка редактора \'Простой галереи Pro\' будет по-прежнему отображаться внутри K2 везде, где есть WYSIWYG редактор.<br /><br />Подробная документация на <a target=\'_blank\' href=\'http://www.joomlaworks.net/docs/simple-image-gallery-pro\'> этой странице</a>."
COM_SIGPRO_RANDOM_ORDER="Случайный порядок"
;COM_SIGPRO_READ_CONTENTS_OF_CAPTION_FILES="Прочитать содержание подписи файлов"
COM_SIGPRO_READ_CONTENTS_OF_CAPTION_FILES="Прочитать содержание подписи файлов"
COM_SIGPRO_REMOVAL_STATUS="Статус удаления"
COM_SIGPRO_REMOVED="Удалено"
COM_SIGPRO_RESULT="Результат"
COM_SIGPRO_REVERSE_DEFAULT_ORDER="Обратный порядок по умолчанию"
COM_SIGPRO_ROOT_FOLDER="Корневая папка для галерей"
COM_SIGPRO_SAVE="Сохранить"
COM_SIGPRO_SAVE_AND_CLOSE="Сохранить и закрыть"
COM_SIGPRO_SELECTED="Выбрано"
COM_SIGPRO_SELECT_ALL="Выбрать ВСЁ"
COM_SIGPRO_SELECT_A_K2_ITEM_TO_CREATE_THE_GALLERY="Выберите элемент K2 для создания галереи"
COM_SIGPRO_SELECT_A_MODULE_POSITION_TO_LOAD_IN_THE_POPUP_WINDOW="Выберите модульную позицию для загрузки во всплывающем окне."
COM_SIGPRO_SELECT_TEMPLATE="Выберите шаблон"
COM_SIGPRO_SELECT_THE_STARTING_FOLDER_FOR_ALL_YOUR_IMAGE_GALLERIES_THIS_IS_GENERALLY_PREFERRED_TO_BE_IMAGESSTORIES_SO_YOU_CAN_EASILY_USE_THE_JOOMLA_MEDIA_MANAGER_TO_UPLOAD_YOUR_IMAGES_INTO_SUBFOLDERS_WITHIN_THIS_FOLDER_EG_INSIDE_IMAGESSTORIESGALLERIESSOMEIMAGEGALLERY="Выберите начальную папку для всех своих галерей. Обычно это \'images/stories\' (в Joomla! 1.5) или просто \'images\' (в Joomla! 1.6+) поэтому можно использовать медиаменеджер Joomla! для загрузки картинок в подпапки этой папки (например внутри: images/stories/galleries/someImageGallery)."
COM_SIGPRO_SETTINGS="Настройки"
COM_SIGPRO_SETTINGS_SAVED="Настройки сохранены"
COM_SIGPRO_SETTINNGS="Настройки"
COM_SIGPRO_SET_CAPTION_WORD_LIMIT_TO_AVOID_HUGE_CAPTIONS_POTENTIALLY_HIDING_THUMBNAIL_IMAGES="Установите лимит слов подписи для избежания огромных подписей, потенциально скрывающих эскизы."
COM_SIGPRO_SHOW_GENERIC_MESSAGES="Показать общие сообщения"
COM_SIGPRO_SINGLE_THUMBNAIL="Один эскиз"
COM_SIGPRO_SITE_GALLERIES="Галереи сайта"
COM_SIGPRO_SMART_THUMBNAIL_RESIZE_BASED_ON_ABOVE_DIMENSIONS="Умные изменения размера эскизов на указанных выше размерах"
COM_SIGPRO_SORT="Сортировка"
COM_SIGPRO_START="Начало"
COM_SIGPRO_STATUS="Статус"
COM_SIGPRO_SYSTEM_INFORMATION="Системная информация"
COM_SIGPRO_SYSTEM_SETTINGS="Системные настройки"
COM_SIGPRO_TARGET_FOLDER_DOES_NOT_EXIST="Целевая папка не существует"
COM_SIGPRO_THE_SPECIFIED_FOLDER_DOES_NOT_EXIST="Указанная папка не существует"
COM_SIGPRO_THIS_GALLERY_IS_EMPTY="Галерея не содержит изображений."
COM_SIGPRO_THIS_IMAGE_IS_NOT_SUPPORTED="Изображение не поддерживается"
COM_SIGPRO_THIS_SITE_IS_EMPTY="Сайт не содержит галерей"
COM_SIGPRO_THUMBNAIL_CACHE_EXPIRATION_TIME="Время действия кэша эскиза"
COM_SIGPRO_THUMBNAIL_CACHE_EXPIRATION_TIME_IN_MINUTES_TO_INSTANTLY_REFRESH_THE_THUMBNAIL_CACHE_FOR_A_PARTICULAR_GALLERY_SIMPLY_SET_THIS_VALUE_TO_0_RELOAD_THE_GALLERY_PAGE_IN_JOOMLA_AND_RESET_IT_TO_120_NEVER_FORGET_THIS_VALUE_TO_0_OR_ELSE_THUMBNAILS_WILL_BE_CREATED_EVERY_TIME_A_GALLERY_IS_LOADED_THUS_OVERLOADING_THE_SERVERS_RESOURCES="Время действия кэша миниатюры в минутах. Чтобы обновить кэш миниатюр для конкретной галереи, установите это значение равным 0, перезагрузите страницу галереи в Joomla и сбросьте его до 120. Никогда не включайте этого значения в 0 постоянно, эскизы будут создаваться каждый раз, когда загружают галерею, тем самым вызывая перегрузку ресурсов сервера."
COM_SIGPRO_THUMBNAIL_HEIGHT="Высота эскиза"
COM_SIGPRO_THUMBNAIL_IMAGE_QUALITY="Качество эскиза"
COM_SIGPRO_THUMBNAIL_VIEWPORT_HEIGHT="Высота просмотра эскиза"
COM_SIGPRO_THUMBNAIL_VIEWPORT_WIDTH="Ширина просмотра эскиза"
COM_SIGPRO_THUMBNAIL_WIDTH="Ширина эскиза"
COM_SIGPRO_TITLE="Заголовок"
COM_SIGPRO_TYPE="Тип"
COM_SIGPRO_TYPE_A_FOLDER_NAME_TO_CREATE_THE_GALLERY="Введите название папки, чтобы создать галерею"
COM_SIGPRO_UPLOAD_HANDLER="Обработчик загрузки"
COM_SIGPRO_UPLOAD_LIMIT="Лимит загрузки"
COM_SIGPRO_USER_GALLERIES="Галереи пользователей"
COM_SIGPRO_USE_COMPONENT_SETTING="-- Наследовать настройки компонента --"
COM_SIGPRO_VERSION="v3.0.8"
COM_SIGPRO_VIEW="Просмотр"
COM_SIGPRO_WRITABLE="Записываемый"
COM_SIGPRO_XML_FORCE_RESIZE_SRC_IMG_IN_PX="Принудительное изменение ширины картинки (в пикселях)"
COM_SIGPRO_XML_FORCE_RESIZE_SRC_IMG_IN_PX_DESC="Заставить изменить размер источника (всплывающего) ширину изображения (и высоту соответственно) в пикселях. Разумные значения \'900\' если ваши изображения превысят этот размер. Эта опция использует сторонний сервис src.sencha.io, поэтому она требует подключения к Интернету. Удалите значение, для отключения этой функции."
COM_SIGPRO_YES="Да"
COM_SIGPRO_YOU_ARE_GOING_TO_DELETE_PERMANENTLY_THE_SELECTED_FOLDERS_FROM_THE_SERVER_ARE_YOU_SURE="Вы собираетесь удалить выбранные папки с сервера. Вы уверены?"
COM_SIGPRO_YOU_ARE_GOING_TO_DELETE_PERMANENTLY_THE_SELECTED_IMAGES_FROM_THE_SERVER_ARE_YOU_SURE="Вы собираетесь удалить выбранные изображения с сервера. Вы уверены?"
COM_SIGPRO_YQL_CACHE="YQL время кэширования (в минутах) для наборов Flickr"
COM_SIGPRO___NONE_SELECTED__="-- Ничего не выбрано --"
JGRID_CHECKBOX_ROW_N="Флажок для строки %d"
SIGPRO="Простая галерея Pro"

;удалено
COM_SIGPRO_PE_COLORBOX_POWERED_BY_JQUERY="ColorBox (на jQuery/возможность слайдшоу)"
COM_SIGPRO_PE_FANCYBOX_POWERED_BY_JQUERY="FancyBox (на jQuery/возможность слайдшоу)"
COM_SIGPRO_PE_FRESCO_POWERED_BY_JQUERY="Fresco (на jQuery/responsive)"
COM_SIGPRO_PE_HIGHSLIDE_WITH_SLIDESHOW="HighSlide (возможность слайдшоу)"
COM_SIGPRO_PE_LIGHTBOX2_POWERED_BY_JQUERY="Lightbox 2 (на jQuery)"
COM_SIGPRO_PE_LIGHTVIEW_POWERED_BY_JQUERY="LightView (на jQuery/возможность слайдшоу)"
COM_SIGPRO_PE_LYTEBOX_WITH_SLIDESHOW="LyteBox (возможность слайдшоу)"
COM_SIGPRO_PE_PRETTYPHOTO_POWERED_BY_JQUERY="PrettyPhoto (на jQuery/возможность слайдшоу)"
COM_SIGPRO_PE_SIMPLEBOX_BY_JOOMLAWORKS="SimpleBox"
COM_SIGPRO_PE_SLIMBOX2_POWERED_BY_JQUERY="Slimbox 2 (на jQuery)"
COM_SIGPRO_PE_SWIPEBOX_POWERED_BY_JQUERY="Swipebox (на jQuery/responsive)"
COM_SIGPRO_PE_YOXVIEW_POWERED_BY_JQUERY="YoxView (на jQuery/возможность слайдшоу)"

COM_SIGPRO_DO_NOT_LOAD_THE_JQUERY_LIBRARY="Не загружать JQuery библиотеку"
COM_SIGPRO_JQUERY_DO_NOT_LOAD_THE_JQUERY_LIBRARY="Не загружать jQuery"
COM_SIGPRO_JQUERY_HANDLING="обработка библиотеки jQuery"
COM_SIGPRO_JQUERY_HANDLING_DESC="Некоторые всплывающие элементы требуют для работы jQuery JavaScript библиотеку. Выберите используемую версию jQuery, или отключите, если загружено другим компонентом, например К2."

COM_SIGPRO_LOAD_LATEST_COPY="Всегда загружать последнюю версию"
COM_SIGPRO_LOAD_V1_10_X_LATEST_COPY="Загрузить v1.10.2"
COM_SIGPRO_LOAD_V1_11_X_LATEST_COPY="Загрузить v1.11.1"
COM_SIGPRO_LOAD_V1_5_X_LATEST_COPY="Загрузить последнюю v1.5.x"
COM_SIGPRO_LOAD_V1_6_X_LATEST_COPY="Загрузить последнюю v1.6.x"
COM_SIGPRO_LOAD_V1_7_X_LATEST_COPY="Загрузить последнюю v1.7.x"
COM_SIGPRO_LOAD_V1_8_X_LATEST_COPY="Загрузить последнюю v1.8.x"
COM_SIGPRO_LOAD_V1_9_X_LATEST_COPY="Загрузить последнюю v1.9.x"
';

$myFiles[JPATH_ADMINISTRATOR .'/language/ru-RU/ru-RU.com_sigpro.menu.ini'] = $myComment .'

COM_SIGPRO="Простая галерея Pro"
SIGPRO="Простая галерея Pro"
';

$myFiles[JPATH_ADMINISTRATOR .'/language/ru-RU/ru-RU.com_sigpro.sys.ini'] = $myComment .'

COM_SIGPRO="Простая галерея Pro"
SIGPRO="Простая галерея Pro"
';

$myFiles[JPATH_ADMINISTRATOR .'/language/ru-RU/ru-RU.plg_content_jw_sigpro.ini'] = $myComment .'

; BACKEND
JW_SIGP_XML_DESC="<h2><a target=\'_blank\' href=\'http://www.joomlaworks.net/\'>JoomlaWorks Плагин для Joomla «Простой галереи Pro» v3.0.8</a></h2><a target=\'_blank\' href=\'http://www.joomlaworks.net/\' title=\'Простая галерея Pro от JoomlaWorks\'><img style=\'float:right;background:#fff;border:1px solid #ccc;padding:2px;margin:0 0 8px 8px;\' src=\'components/com_sigpro/images/SimpleImageGalleryPro_300x88_24.png\' border=\'0\' alt=\'Простая галерея Pro от JoomlaWorks\' /></a>Добавление галереи изображений в вашу статью Joomla теперь супер легко и просто с помощью волшебного плагина для Joomla \'Простая галерея Pro\'<br /><br />Плагин может превратить любую папку с изображениями, находящуюся внутри вашего сайта Joomla в фотогалерею в сеточном стиле с отличными превью на lightbox. И все это с помощью простого тега плагина, как <b>{gallery}myphotos{/gallery}</b>.<br /><br /><a href=\'index.php?option=com_sigpro&view=settings\'>ВНИМАНИЕ! СЕЙЧАС ВСЕ НАСТРОЙКИ ПЛАГИНА ВНУТРИ КОМПОНЕНТА</a>.<br /><br />Для получения дополнительной информации обратитесь к следующим ресурсам:<ul><li><a target=\'_blank\' href=\'http://www.joomlaworks.net/simple-image-gallery-pro\'>Страница продукта Простой галереи Pro</a></li><li><a target=\'_blank\' href=\'http://www.joomlaworks.net/docs/simple-image-gallery-pro\'>Документация Простой галереи Pro</a></li><li><a target=\'_blank\' title=\'Посетить JoomlaWorks!\' href=\'http://www.joomlaworks.net/\'>Подробнее о всех расширениях JoomlaWorks для Joomla и других систем.</a></li></ul><b>Простая галерея Pro</b> это <a target=\'_blank\' href=\'http://www.joomla.org/\'>Joomla!</a> расширение, разработанное <a target=\'_blank\' title=\'Посетить JoomlaWorks!\' href=\'http://www.joomlaworks.net\'>JoomlaWorks</a>.<br /><br />Авторское право &copy; 2006 - 2015 JoomlaWorks Ltd.<br /><br /><i>(Последнее обновление: 22 Июня, 2015 - Версия 3.0.8)</i>"

; FRONTEND
JW_SIGP_LABELS_01="Заголовок"
JW_SIGP_LABELS_02="<b>Описание</b><br />Разрешено использовать HTML"
JW_SIGP_LABELS_03="Нажмите для просмотра!"
JW_SIGP_LABELS_04="<b>Подсказка:</b> Наведите курсор на правую или левую сторону картинки, для просмотра следующего или предыдущего изображения.<br /><br />Вы просматриваете изображения из статьи:"
JW_SIGP_LABELS_05="Скачать картинку"
JW_SIGP_LABELS_06="Картинки GIF не обрабатываются этим сервером. Используйте JPEG и PNG или обновите библиотеку GD до последней версии."
JW_SIGP_LABELS_07="Проблема конвертации исходного изображения."
JW_SIGP_LABELS_08="Нажмите для увеличения"
JW_SIGP_LABELS_09="Нажмите для увеличения"
JW_SIGP_LABELS_10="<b>Подсказка:</b> Наведите курсор на правую или левую сторону картинки, для просмотра следующего или предыдущего изображения.<br /><br />Вы просматриваете изображения из набора Flickr"
JW_SIGP_LABELS_11="опубликовано в"
JW_SIGP_LABELS_12="от"
JW_SIGP_LABELS_13="Скачать оригинал с Flickr"
JW_SIGP_PLG_CACHE_FOLDER_UNWRITABLE="Уведомление галереи: Папка <b>/кэша</b> Joomla не доступна для записи. Исправьте разрешения этой папки, очистите кэш сайта и повторите попытку"
JW_SIGP_PLG_DL_01="Ошибка компонента галереи"
JW_SIGP_PLG_DL_02="Вы не можете скачать файл по следующим причинам"
JW_SIGP_PLG_DL_03="Файл недоступен"
JW_SIGP_PLG_DL_04="Неверный путь к набору"
JW_SIGP_PLG_DL_05="Незаконное выполнение кода"
JW_SIGP_PLG_DL_06="Воспользуйтесь следующими страницами"
JW_SIGP_PLG_DL_07="Вернуться на предыдущую"
JW_SIGP_PLG_DL_08="Перейти на главную"
JW_SIGP_PLG_DL_09="Главная"
JW_SIGP_PLG_DL_10="Если у вас возникли сложности, пожалуйста, свяжитесь с Администратором этого сайта."
JW_SIGP_PLG_FLICKRSET="Показать весь набор на Flickr"
JW_SIGP_PLG_FLICKR_PHOTOSET_NOT_AVAILABLE="Уведомление галереи: набор Flickr не существует или доступ к нему ограничен."
JW_SIGP_PLG_GALLERY_RENDER_PROBLEM="Проблема визуализации вашей галереи. Убедитесь, что используемая в плагине меток папка Простой галереи Pro существует и содержит правильные файлы картинок. Плагин не смог найти папку:"
JW_SIGP_PLG_GD_MISSING_NOTICE="Уведомление галереи: Похоже «Библиотека GD изображений» PHP не установлена/не включена в вашей системе. Попросите хостинговую компанию включить/установить её."
JW_SIGP_PLG_PHP5_REQUIRED="Для отрисовки наборов Flickr в Простой галерее Pro, требуется PHP 5. Обратитесь к вашей хостинговой компании за обновлением до PHP5."
JW_SIGP_PLG_PRINT_MESSAGE="Смотреть встроенную онлайн галерею в"
JW_SIGP_PLG_SIGFREE_NOTICE="Бесплатная версия галереи изображений установлена на сайте. Пожалуйста, отключите её, чтобы использовать более продвинутую версию PRO"
JW_SIGP_PLG_THIS_LAYOUT_REQUIRES_JQUERY="Примечание галереи: Выбранный макет галереи требует загрузки jQuery на сайт. Если вы ещё не загружаете jQuery, просто включите параметр \'Обработка библиотеки jQuery\' в настройках Простой галереи Pro."

;удалено
JW_SIGP_PLG_NOTICE_00="Бесплатная версия галереи изображений установлена на сайте. Пожалуйста, отключите её, чтобы использовать более продвинутую версию PRO"
JW_SIGP_PLG_NOTICE_01="Примечание галереи: Похоже  \'GD Image Library\' не установлена/не включена в вашей системе. Попросите вашего хостера включить/установить её."
JW_SIGP_PLG_NOTICE_02="Примечание галереи: Папка <b>/cache</b> Joomla не доступна для записи. Исправьте разрешения этой папки, очистите кэш Вашего сайта и повторите попытку."
JW_SIGP_PLG_NOTICE_03="Проблема рендеринга изображения галереи. Убедитесь, что используемая в галерее папка существует и содержит нужные файлы изображений. Плагину не удалось найти папку:"
';

$myFiles[JPATH_ADMINISTRATOR .'/language/ru-RU/ru-RU.plg_content_jw_sigpro.sys.ini'] = $myComment .'

JW_SIGP_XML_DESC="<h2><a target=\'_blank\' href=\'http://www.joomlaworks.net/\'>JoomlaWorks Плагин для Joomla «Простой галереи Pro» v3.0.8</a></h2><a target=\'_blank\' href=\'http://www.joomlaworks.net/\' title=\'Простая галерея Pro от JoomlaWorks\'><img style=\'float:right;background:#fff;border:1px solid #ccc;padding:2px;margin:0 0 8px 8px;\' src=\'components/com_sigpro/images/SimpleImageGalleryPro_300x88_24.png\' border=\'0\' alt=\'Простая галерея Pro от JoomlaWorks\' /></a>Добавление галереи изображений в вашу статью Joomla теперь супер легко и просто с помощью волшебного плагина для Joomla «Простая галерея Pro»<br /><br />Плагин может превратить любую папку с изображениями, находящуюся внутри вашего сайта Joomla в фотогалерею в сеточном стиле с отличными превью на lightbox. И все это с помощью простого тега плагина, как <b>{gallery}myphotos{/gallery}</b>.<br /><br /><a href=\'index.php?option=com_sigpro&view=settings\'>ВНИМАНИЕ! СЕЙЧАС ВСЕ НАСТРОЙКИ ПЛАГИНА ВНУТРИ КОМПОНЕНТА</a>.<br /><br />Для получения дополнительной информации обратитесь к следующим ресурсам:<ul><li><a target=\'_blank\' href=\'http://www.joomlaworks.net/simple-image-gallery-pro\'>Страница продукта Простой галереи Pro</a></li><li><a target=\'_blank\' href=\'http://www.joomlaworks.net/docs/simple-image-gallery-pro\'>Документация Простой галереи Pro</a></li><li><a target=\'_blank\' title=\'Посетить JoomlaWorks!\' href=\'http://www.joomlaworks.net/\'>Подробнее о всех расширениях JoomlaWorks для Joomla и других систем.</a></li></ul><b>Простая галерея Pro</b> это <a target=\'_blank\' href=\'http://www.joomla.org/\'>Joomla!</a> расширение, разработанное <a target=\'_blank\' title=\'Посетить JoomlaWorks!\' href=\'http://www.joomlaworks.net\'>JoomlaWorks</a>.<br /><br />Авторское право &copy; 2006 - 2013 JoomlaWorks Ltd.<br /><br /><i>(Последнее обновление: 22 Июня, 2015 - Версия 3.0.8)</i>"
';

$myFiles[JPATH_ADMINISTRATOR .'/language/ru-RU/ru-RU.plg_editors-xtd_jw_sigpro.ini'] = $myComment .'

PLG_EDITORS-XTD_JW_SIGPRO_IMAGE_GALLERIES="Простая галерея Pro"
BUTTON - SIMPLE IMAGE GALLERY PRO="Кнопка - Простая галерея PRO"
';

$myFiles[JPATH_ADMINISTRATOR .'/language/ru-RU/ru-RU.plg_editors-xtd_jw_sigpro.sys.ini'] = $myComment .'

PLG_EDITORS-XTD_JW_SIGPRO_IMAGE_GALLERIES="Простая галерея Pro"
BUTTON - SIMPLE IMAGE GALLERY PRO="Кнопка - Простая галерея PRO"
';

$myFiles[JPATH_ADMINISTRATOR .'/language/ru-RU/ru-RU.plg_k2_jw_sigpro.ini'] = $myComment .'

K2 - SIMPLE IMAGE GALLERY PRO="K2 - Простая Галерея Pro"
';

$myFiles[JPATH_ADMINISTRATOR .'/language/ru-RU/ru-RU.plg_k2_jw_sigpro.sys.ini'] = $myComment .'

K2 - SIMPLE IMAGE GALLERY PRO="K2 - Простая Галерея Pro"
';

class a50067c65f639371adea09509da49a56InstallerScript {
	public function __constructor(JAdapterInstance $adapter) {}
	function getParam( $name ) {
		$db = JFactory::getDbo();
		$db->setQuery('SELECT manifest_cache FROM #__extensions WHERE name = "'. JLANG_NOMBER .'"');
		$manifest = json_decode( $db->loadResult(), true );
		return $manifest[ $name ];
	}
	function preflight( $type, $parent ) {
		$jversion = new JVersion();
		$this->release = $parent->get( "manifest" )->version;
		echo '<style type="text/css">label,section{margin:0;padding:0;border:0;font-size:100%;font:inherit;vertical-align:baseline;display: block;}.jl_osnova{background:#104675;background:-moz-linear-gradient(left, #104675 11%, #2B66A3 50%, #104675 89%);background:-webkit-gradient(linear, left top, right top, color-stop(11%,#104675), color-stop(50%,#2B66A3), color-stop(89%,#104675));background:-webkit-linear-gradient(left, #104675 11%,#2B66A3 50%,#104675 89%);background:-o-linear-gradient(left, #104675 11%,#2B66A3 50%,#104675 89%);background:-ms-linear-gradient(left,  #104675 11%,#2B66A3 50%,#104675 89%);background:linear-gradient(to right,  #104675 11%,#2B66A3 50%,#104675 89%);border:solid 3px #565656;-moz-border-radius:8px;-webkit-border-radius:8px;-ms-border-radius:8px;-o-border-radius:8px;border-radius:8px;width:60%;min-width:500px;min-height:250px;height:auto;margin:auto;color:#fff;}.jl_spoiler{width:99%;min-width:490px;margin:10px auto;text-align:left;font-weight: normal;}.jl_spoiler label{padding:5px 30px;margin-top:8px;position:relative;z-index:20;display:block;height:25px;cursor:pointer;color:#727272;text-shadow:1px 1px 1px rgba(255,255,255,0.8);line-height:23px;font-size:18px;background:#e0e0e0;background:-moz-linear-gradient(top, #e0e0e0 0%, #eeeeee 33%, #828282 100%);background:-webkit-gradient(linear, left top, left bottom, color-stop(0%,#e0e0e0), color-stop(33%,#eeeeee), color-stop(100%,#828282));background:-webkit-linear-gradient(top, #e0e0e0 0%,#eeeeee 33%,#828282 100%);background:-o-linear-gradient(top, #e0e0e0 0%,#eeeeee 33%,#828282 100%);background:-ms-linear-gradient(top, #e0e0e0 0%,#eeeeee 33%,#828282 100%);background:linear-gradient(to bottom, #e0e0e0 0%,#eeeeee 33%,#828282 100%);filter: progid:DXImageTransform.Microsoft.gradient( startColorstr=\'#e0e0e0\', endColorstr=\'#828282\',GradientType=0 );-webkit-box-shadow:inset 0 1px 0 #ffffff;-moz-box-shadow:inset 0 1px 0 #ffffff;box-shadow:inset 0 1px 0 #ffffff;-webkit-border-radius:4px 4px 0 0;-moz-border-radius:4px 4px 0 0;border-radius:4px 4px 0 0;border-bottom:1px solid #C1C1C1;border-right:1px solid #EAEAEA;border-left:1px solid #EAEAEA;}.jl_spoiler label:hover,.jl_spoiler input:checked + label,.jl_spoiler input:checked + label:hover{background:#dbdbdb;background:-moz-linear-gradient(top, #dbdbdb 2%, #828282 14%, #d8d8d8 85%, #828282 100%);background:-webkit-gradient(linear, left top, left bottom, color-stop(2%,#dbdbdb), color-stop(14%,#828282),color-stop(85%,#d8d8d8),color-stop(100%,#828282));background:-webkit-linear-gradient(top, #dbdbdb 2%,#828282 14%,#d8d8d8 85%,#828282 100%);background:-o-linear-gradient(top, #dbdbdb 2%,#828282 14%,#d8d8d8 85%,#828282 100%);background:-ms-linear-gradient(top, #dbdbdb 2%,#828282 14%,#d8d8d8 85%,#828282 100%);background:linear-gradient(to bottom, #dbdbdb 2%,#828282 14%,#d8d8d8 85%,#828282 100%);filter: progid:DXImageTransform.Microsoft.gradient( startColorstr=\'#dbdbdb\', endColorstr=\'#828282\',GradientType=0 );color: #565656;}.jl_spoiler label:hover:after,.jl_spoiler input:checked + label:hover:after{content:\'\25BC\';position: absolute;width: 24px;height: 24px;left: 7px;top: 6px;font-size:15px;color:#727272;}.jl_spoiler input:checked + label{}.jl_spoiler input:checked + label:hover:after{content:\'\25B2\';}.jl_spoiler input{display: none;}.jl_spoiler section{background:#eeeeee;background:-moz-linear-gradient(top, #eeeeee 0%, #d3d3d3 100%);background:-webkit-gradient(linear, left top, left bottom, color-stop(0%,#eeeeee), color-stop(100%,#d3d3d3));background:-webkit-linear-gradient(top, #eeeeee 0%,#d3d3d3 100%);background:-o-linear-gradient(top, #eeeeee 0%,#d3d3d3 100%);background:-ms-linear-gradient(top, #eeeeee 0%,#d3d3d3 100%);background:linear-gradient(to bottom, #eeeeee 0%,#d3d3d3 100%);filter: progid:DXImageTransform.Microsoft.gradient( startColorstr=\'#eeeeee\', endColorstr=\'#d3d3d3\',GradientType=0 );margin-top:-1px;overflow: hidden;max-height:0px;position:relative;z-index:10;border:1px solid #EAEAEA;border-bottom:none;-webkit-border-radius:0 0 4px 4px;-moz-border-radius:0 0 4px 4px;border-radius:0 0 4px 4px;}.jl_spoiler section p{color:#444;text-shadow:0px 1px 1px rgba(255,255,255, 0.8);padding:10px;font-size:16px;line-height:20px;}.jl_spoiler input:checked ~ section{-webkit-transition: max-height 1.5s ease-in-out, box-shadow 3.1s linear;-moz-transition: max-height 1.5s ease-in-out, box-shadow 3.1s linear;-o-transition: max-height 1.5s ease-in-out, box-shadow 3.1s linear;-ms-transition: max-height 1.5s ease-in-out, box-shadow 3.1s linear;transition: max-height 1.5s ease-in-out, box-shadow 3.1s linear;box-shadow: inset 0px 0px 0px 5px rgba(155,155,155,0.3);}.jl_spoiler input:checked ~ section.jl_big{max-height:5000px;border:1px solid #fff;border-bottom:none;-webkit-border-radius:0 0 4px 4px;-moz-border-radius:0 0 4px 4px;border-radius:0 0 4px 4px;-webkit-transition: max-height 1.5s ease-in-out, box-shadow 0.6s linear;-moz-transition: max-height 1.5s ease-in-out, box-shadow 0.6s linear;-o-transition: max-height 1.5s ease-in-out, box-shadow 0.6s linear;-ms-transition: max-height 1.5s ease-in-out, box-shadow 0.6s linear;transition: max-height 1.5s ease-in-out, box-shadow 0.6s linear;}.jl_info{min-height:120px;}</style>';
		if(version_compare(JVERSION,'3.0','<')) {
			echo '<style type="text/css">.label,.badge {font-size: 11.844px;font-weight: bold;line-height: 14px;color: #fff;vertical-align: baseline;white-space: nowrap;text-shadow: 0 -1px 0 rgba(0,0,0,0.25);background-color: #999;}.label {padding: 1px 4px 2px;-webkit-border-radius: 3px;-moz-border-radius: 3px;border-radius: 3px;}.badge {padding: 1px 9px 2px;-webkit-border-radius: 9px;-moz-border-radius: 9px;border-radius: 9px;}a.label:hover,a.badge:hover {color: #fff;text-decoration: none;cursor: pointer;}.label-important,.badge-important {background-color: #b94a48;}.label-important[href],.badge-important[href] {background-color: #953b39;}.label-warning,.badge-warning {background-color: #f89406;}.label-warning[href],.badge-warning[href] {background-color: #c67605;}.label-success,.badge-success {background-color: #468847;}.label-success[href],.badge-success[href] {background-color: #356635;}.label-info,.badge-info {background-color: #3a87ad;}.label-info[href],.badge-info[href] {background-color: #2d6987;}.label-inverse,.badge-inverse {background-color: #333;}.label-inverse[href],.badge-inverse[href] {background-color: #1a1a1a;}.btn .label,.btn .badge {position: relative;top: -1px;}.btn-mini .label,.btn-mini .badge {top:0;}</style>';
		}
		echo '<div class="jl_osnova">
	<div class="jl_info">
	<a href="http://www.joomlang.ru" title="Перейти на сайт" target="_blank"><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAGQAAABPCAYAAAAdiWChAAAACXBIWXMAAAsTAAALEwEAmpwYAAABNmlDQ1BQaG90b3Nob3AgSUNDIHByb2ZpbGUAAHjarY6xSsNQFEDPi6LiUCsEcXB4kygotupgxqQtRRCs1SHJ1qShSmkSXl7VfoSjWwcXd7/AyVFwUPwC/0Bx6uAQIYODCJ7p3MPlcsGo2HWnYZRhEGvVbjrS9Xw5+8QMUwDQCbPUbrUOAOIkjvjB5ysC4HnTrjsN/sZ8mCoNTIDtbpSFICpA/0KnGsQYMIN+qkHcAaY6addAPAClXu4vQCnI/Q0oKdfzQXwAZs/1fDDmADPIfQUwdXSpAWpJOlJnvVMtq5ZlSbubBJE8HmU6GmRyPw4TlSaqo6MukP8HwGK+2G46cq1qWXvr/DOu58vc3o8QgFh6LFpBOFTn3yqMnd/n4sZ4GQ5vYXpStN0ruNmAheuirVahvAX34y/Axk/96FpPYgAAACBjSFJNAAB6JQAAgIMAAPn/AACA6AAAUggAARVYAAA6lwAAF2/XWh+QAAAE80lEQVR42uydy24bNxSG/0NxKMmWCxdIF44lOUACtAXyPn2KLvIOXeXluvCmF6RJZCdNiy4CW7EkDocnC11s3WY4FSWP7PMDWkjySIf8eHh+ckZjqtVqyBGjWqKKx0ibfoAu+gM7HK5972YwQOvoKGqL/vn0Cd89ebL0umk0SseotI4am7UWWqmV7+XFV4rophninIN3LkowSmvo/E4slSE7jm0nGVL0BVyB1KcKx1YePESVkgARICIBIkBEAkSAiASIABEJkIiKtW0iQCRDHmjjIu/2ChDJENF9K0pOqwrPDEo/MiBxnUxcV1Tl2LYGxLzWwMjGiaZpojau8VrDx4pNm/0Aoj0AH2n0eBN1HNYYUNFi0zvJkY2BjF2Br+R8TAAoWmyZuCyxvSIBIhIgAuQ+F1m83cuoBEg5ILL9LhIgAkQkQASISICIBIgAEQmQvVvMiqoilgypkJKfz2XKqpRcJkCqNF2pZy8FSKVE4rLE9opylAmQytSP5NVbAVLeBY22WD5IpqxK6ds22EdaqXOF2+mh4OfG3ybFdnst9Y4BTXGA0Ki/cUCDegsA0Bz0ozbUDm9Qq3j94F8c0pEDtB5f+lpwv6zCD/zcjntV+PGlvePK8VBjWxnf8aXdPENa6SDevKUARBzTLRcntn7SHH9W5HxreQtk2VzbN9/trTfRj3SBecsO4k4IJl5sadIEYGNFxu7MoJ/d2qqWAgC7e5e12OnRIezBuuP8JEHqt7NSJ92zE7qB6W+auc8fOow/nxr8qAlD3g6QYCjrMuMRZQj/dpLgWQ245vXOQOUsLxYfQVA4MDMeWYbw+Wl9+FwT+pxv0/TKgtM18z1LgO5Zvn22Fgq7jkE/wNmsBNGIWjgrAQIAXNfAMjeKYKwCwq5rcO3nD2QGbjoGRgH6veU8L64vLK7aZpe/OuT76OSgetkxUMDM6YUsYO4C4VHHoO+XDyQAloE0A9yZyYNCAPhAIZrdDNHvJwlGfHfLBDgk4CZiDJaB/04NPmbLPLqTbY93jvGNAroJIfXjY8r+IEIv+eKCoWGLG0m6Z3nQMXOdtE09r68pXhw/gGO93r28rNHsexMCElrdhwM/X6hXZoj34SNl2DFoXORPXbvcQ9LvRtgnXZ4a1AAcKSDjeSgaALsXR/gyGAJEpdIqN0121779+s0agPaHmUGC6xp88Yu2V5XcXC7uAkouyrklQ8CblPFIRNOH7lkcqEUgFbgVHgH4/u90L0f8pk1PehZQdAcIc/B0tbU6ANE8kDKd14t6XgAMgB75yeSpMZncOyb8xioJBZUbHnXCTw4dEqDfx4W8rzVFlTEqCsDhhaWQA8r0rCKIpn2hAKV7IzQDOuWgxLQSahMaFH8K3GsgHuCfDtRxvcCmtlRwx/F0ARmSRVowLBf1P1K+yuvpsqP4OuDiAgZwqCQ7VgL5NR3brEO1em/l3/BNOh526oX3XuNpIRcYa2zvZAbRPYujBSgMIPA/9LHrGgwLLDRhvIdjLgRGEZAZlNadbsom9ePt02RWH1YMeHZdU7jlbkimqcK6uuZCOXZdg4yBG74t6m9Sxg/j7Y2ZrtoGCQHDFWfDeFK0pw5uAkJg/A8gs2xwZwbeT1bTANTCosF7Xkobmq4tCPjoGN0PqYCIAGQG5nPb4C/HGARY2ToBLzRNL7sUEFsAsq52xFysiyb6OgCdX7XRDrdxdgAAAABJRU5ErkJggg==" style="float:left; padding:10px;"/></a>
	<p style="text-align:center;">Благодарим за выбор нашего русификатора.<br />Нашли ошибки или желаете улучшить перевод? Сообщите по <a style="color:#000" href="mailto:pochta@joomlang.ru?subject='. JLANG_NAME .' русификатор">почте</a> или оставьте сообщение на <a style="color:#000" href="http://www.joomlang.ru/forum.html" target="_blank">форуме</a>. Ваши исправления и замечания будут обязательно учтены и добавлены в последующие обновления.<br />
	<p><span class="label label-success"><em>&#10004; Примечание.</em></span></p><p style="text-align:center;">Помогите другим участникам, добавив свою локализацию в каталог. <a style="color:#000" href="http://www.joomlang.ru/catalog/submit.html" target="_blank">Добавить</a> без регистрации.</p>
	<p style="text-align:center;font-size:20px;">Установлена версия -  '. $this->release .'</p>
	</div>
	<div class="jl_spoiler">';
	}
	public function postflight($route, JAdapterInstance $adapter) {
		echo '<div>
			<input id="raznoe" name="vkladka-4" type="checkbox" />
			<label for="raznoe">Дополнительно</label>
				<section class="jl_big"><p>';
			if(version_compare(JVERSION,'3.0','<')) {
				$myFile = JPATH_ADMINISTRATOR .'/manifests/files/'.JLANG_NOMBER.'.xml';
				$myContent = file_get_contents($myFile);
				$myPoisk = (array('<fileset></fileset>', '<fileset>
		<files folder="admin" target="administrator/language/ru-RU">
			<filename>ru-RU.com_sigpro.ini</filename>
            <filename>ru-RU.com_sigpro.menu.ini</filename>
            <filename>ru-RU.com_sigpro.sys.ini</filename>
            <filename>ru-RU.plg_content_jw_sigpro.ini</filename>
            <filename>ru-RU.plg_content_jw_sigpro.sys.ini</filename>
            <filename>ru-RU.plg_editors-xtd_jw_sigpro.ini</filename>
            <filename>ru-RU.plg_editors-xtd_jw_sigpro.sys.ini</filename>
            <filename>ru-RU.plg_k2_jw_sigpro.ini</filename>
            <filename>ru-RU.plg_k2_jw_sigpro.sys.ini</filename>
		</files>
		<files folder="site" target="language/ru-RU">
			<filename>ru-RU.files_'. JLANG_NOMBER .'.sys.ini</filename>
		</files>
	</fileset>'));
				$myContent = str_replace($myPoisk[0], $myPoisk[1], $myContent);
				file_put_contents($myFile,$myContent);
				echo 'Записи удаления файлов для Joomla 2.5 добавлены<br/>';
			} else {
				echo 'Записи удаления файлов для Joomla 3.x не требуются<br/>';
			}
			echo '</p></section></div></div></div>';
	}
	public function install(JAdapterInstance $adapter) {
		echo '<div>
		<input id="faily" name="vkladka-1" type="checkbox" />
		<label for="faily">Установленные файлы</label>
		<section class="jl_big"><p>';
		$myFiles = $GLOBALS['myFiles'];
		foreach ($myFiles as $myFile => $myContent) {
			$myPoisk = (array('JOOMLANG_VER_COM', $this->release));
			$myContent = str_replace($myPoisk[0], $myPoisk[1], $myContent);
			@file_put_contents($myFile,$myContent);
			if (is_writable($myFile)) {
				echo '<strong>Файл</strong> <span style="cursor: help;" title=\''. $myFile .'\'>'. basename($myFile).'</span> <span class="label label-success pull-right">создан</span><br/>';
			} else {
				echo '<strong>Файл</strong> <span style="cursor: help;" title=\''. $myFile .'\'>'. basename($myFile).'</span> <span class="label label-important pull-right">не создан</span><br/>';
			}
		}
		echo '</p></section></div>';
		echo '<audio autoplay><source src="'. JL_COMPLETE .'"></audio>';
	}
	function update( $parent ) {
		$this->install($parent);
	}
	function uninstall( $parent ) {
		$myFiles = $GLOBALS['myFiles'];
		foreach($myFiles as $myFile => $myContent) {
			if(is_file($myFile)) JFile::delete($myFile);
		}
		echo '<hr/><p style="font-size:25px;text-align:center; font-family: Helvetica,sans-serif;font-weight: 900;color:#4A6DA5;text-shadow: 0 1px 3px #000;">Русификатор '. JLANG_NAME .' удалён</p><hr/>';
	}
}