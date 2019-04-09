<?php

/* @nucleus/content/spacer.html.twig */
class __TwigTemplate_fbcdf92156182572a53e087bbc8428831f41a856b24331106f64400304a7de28 extends Twig_Template
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
        if ( !($context["particle"] ?? null)) {
            // line 2
            echo "    ";
            $context["enabled"] = $this->getAttribute($this->getAttribute(($context["gantry"] ?? null), "config", []), "get", [0 => (("particles." . $this->getAttribute(($context["segment"] ?? null), "type", [])) . ".enabled"), 1 => 1], "method");
            // line 3
            echo "    ";
            $context["spacer"] = $this->getAttribute($this->getAttribute(($context["gantry"] ?? null), "config", []), "getJoined", [0 => ("particles." . $this->getAttribute(($context["segment"] ?? null), "type", [])), 1 => $this->getAttribute(($context["segment"] ?? null), "attributes", [])], "method");
        }
        // line 5
        echo "
";
        // line 6
        if ((($context["enabled"] ?? null) && ((null === $this->getAttribute($this->getAttribute(($context["segment"] ?? null), "attributes", []), "enabled", [])) || $this->getAttribute($this->getAttribute(($context["segment"] ?? null), "attributes", []), "enabled", [])))) {
            // line 7
            echo "    <div class=\"spacer";
            echo twig_escape_filter($this->env, (($this->getAttribute(($context["segment"] ?? null), "classes", [])) ? ((" " . twig_escape_filter($this->env, twig_join_filter($this->getAttribute(($context["segment"] ?? null), "classes", []), " ")))) : ("")), "html", null, true);
            echo "\"></div>
";
        }
    }

    public function getTemplateName()
    {
        return "@nucleus/content/spacer.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  33 => 7,  31 => 6,  28 => 5,  24 => 3,  21 => 2,  19 => 1,);
    }

    /** @deprecated since 1.27 (to be removed in 2.0). Use getSourceContext() instead */
    public function getSource()
    {
        @trigger_error('The '.__METHOD__.' method is deprecated since version 1.27 and will be removed in 2.0. Use getSourceContext() instead.', E_USER_DEPRECATED);

        return $this->getSourceContext()->getCode();
    }

    public function getSourceContext()
    {
        return new Twig_Source("", "@nucleus/content/spacer.html.twig", "/home/dobrod/airbagua.com/www/media/gantry5/engines/nucleus/templates/content/spacer.html.twig");
    }
}
