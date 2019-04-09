<?php

/* @particles/analytics.html.twig */
class __TwigTemplate_5483eeedeb6b0886dfcfa149cb268108abf22f9ea0a2530e696dea2fb2a27b32 extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->parent = false;

        $this->blocks = [
        ];
    }

    protected function doDisplay(array $context, array $blocks = [])
    {
        // line 1
        if ($this->getAttribute($this->getAttribute(($context["particle"] ?? null), "ua", []), "code", [])) {
            // line 2
            echo "    ";
            ob_start();
            // line 3
            echo "    (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
    (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
    m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
    })";
            // line 6
            if ($this->getAttribute($this->getAttribute(($context["particle"] ?? null), "ua", []), "debug", [])) {
                echo "(window,document,'script','//www.google-analytics.com/analytics_debug.js','ga');";
            } else {
                echo "(window,document,'script','//www.google-analytics.com/analytics.js','ga');";
            }
            // line 7
            echo "    ga('create', '";
            echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute(($context["particle"] ?? null), "ua", []), "code", []), "html", null, true);
            echo "', 'auto');
    ";
            // line 8
            if ($this->getAttribute($this->getAttribute(($context["particle"] ?? null), "ua", []), "anonym", [])) {
                // line 9
                echo "    ga('set', 'anonymizeIp', true);
    ";
            }
            // line 11
            echo "    ";
            if ($this->getAttribute($this->getAttribute(($context["particle"] ?? null), "ua", []), "ssl", [])) {
                // line 12
                echo "    ga('set', 'forceSSL', true);
    ";
            }
            // line 14
            echo "    ga('send', 'pageview');
    ";
            $context["script"] = ('' === $tmp = ob_get_clean()) ? '' : new Twig_Markup($tmp, $this->env->getCharset());
            // line 16
            echo "
    ";
            // line 17
            $this->getAttribute($this->getAttribute(($context["gantry"] ?? null), "document", []), "addInlineScript", [0 => ($context["script"] ?? null), 1 => 0], "method");
        }
    }

    public function getTemplateName()
    {
        return "@particles/analytics.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  60 => 17,  57 => 16,  53 => 14,  49 => 12,  46 => 11,  42 => 9,  40 => 8,  35 => 7,  29 => 6,  24 => 3,  21 => 2,  19 => 1,);
    }

    /** @deprecated since 1.27 (to be removed in 2.0). Use getSourceContext() instead */
    public function getSource()
    {
        @trigger_error('The '.__METHOD__.' method is deprecated since version 1.27 and will be removed in 2.0. Use getSourceContext() instead.', E_USER_DEPRECATED);

        return $this->getSourceContext()->getCode();
    }

    public function getSourceContext()
    {
        return new Twig_Source("", "@particles/analytics.html.twig", "/home/dobrod/airbagua.com/www/media/gantry5/engines/nucleus/particles/analytics.html.twig");
    }
}
