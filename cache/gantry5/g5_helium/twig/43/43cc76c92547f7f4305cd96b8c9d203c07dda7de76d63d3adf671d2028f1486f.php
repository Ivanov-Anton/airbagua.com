<?php

/* partials/error_head.html.twig */
class __TwigTemplate_2fdf2708aa03afbe5d2b65045fa173ebc5b9fc4515dd28f4e9dec580705b8829 extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        // line 1
        $this->parent = $this->loadTemplate("partials/page_head.html.twig", "partials/error_head.html.twig", 1);
        $this->blocks = [
            'head_application' => [$this, 'block_head_application'],
        ];
    }

    protected function doGetParent(array $context)
    {
        return "partials/page_head.html.twig";
    }

    protected function doDisplay(array $context, array $blocks = [])
    {
        $this->parent->display($context, array_merge($this->blocks, $blocks));
    }

    // line 3
    public function block_head_application($context, array $blocks = [])
    {
        // line 4
        echo "<meta charset=\"utf-8\" />
    <meta http-equiv=\"content-type\" content=\"text/html; charset=utf-8\" />
    <title>";
        // line 6
        echo twig_escape_filter($this->env, (((isset($context["errorcode"]) || array_key_exists("errorcode", $context))) ? (_twig_default_filter(($context["errorcode"] ?? null), 500)) : (500)), "html", null, true);
        echo " ";
        echo twig_escape_filter($this->env, (((isset($context["error"]) || array_key_exists("error", $context))) ? (_twig_default_filter(($context["error"] ?? null), $this->env->getExtension('Gantry\Component\Twig\TwigExtension')->transFilter("GANTRY5_ENGINE_UNKNOWN_ERROR"))) : ($this->env->getExtension('Gantry\Component\Twig\TwigExtension')->transFilter("GANTRY5_ENGINE_UNKNOWN_ERROR"))), "html", null, true);
        echo "</title>
    ";
        // line 7
        $this->getAttribute($this->getAttribute(($context["gantry"] ?? null), "document", []), "errorPage", [0 => true], "method");
        // line 8
        $this->getAttribute($this->getAttribute(($context["gantry"] ?? null), "platform", []), "finalize", [], "method");
        // line 9
        echo twig_join_filter($this->getAttribute(($context["gantry"] ?? null), "styles", [0 => "head", 1 => true], "method"), "
    ");
        echo "
    ";
        // line 10
        echo twig_join_filter($this->getAttribute(($context["gantry"] ?? null), "scripts", [0 => "head", 1 => true], "method"), "
    ");
    }

    public function getTemplateName()
    {
        return "partials/error_head.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  50 => 10,  45 => 9,  43 => 8,  41 => 7,  35 => 6,  31 => 4,  28 => 3,  11 => 1,);
    }

    /** @deprecated since 1.27 (to be removed in 2.0). Use getSourceContext() instead */
    public function getSource()
    {
        @trigger_error('The '.__METHOD__.' method is deprecated since version 1.27 and will be removed in 2.0. Use getSourceContext() instead.', E_USER_DEPRECATED);

        return $this->getSourceContext()->getCode();
    }

    public function getSourceContext()
    {
        return new Twig_Source("", "partials/error_head.html.twig", "/home/dobrod/airbagua.com/www/media/gantry5/engines/nucleus/twig/partials/error_head.html.twig");
    }
}
