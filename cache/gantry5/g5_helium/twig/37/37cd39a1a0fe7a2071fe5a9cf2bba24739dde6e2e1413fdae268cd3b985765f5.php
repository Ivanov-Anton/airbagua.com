<?php

/* @particles/date.html.twig */
class __TwigTemplate_d5b3f41e9e749b595ef8ab70550b455a962b07489d98600f428924f61de71d75 extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        // line 1
        $this->parent = $this->loadTemplate("@nucleus/partials/particle.html.twig", "@particles/date.html.twig", 1);
        $this->blocks = [
            'particle' => [$this, 'block_particle'],
        ];
    }

    protected function doGetParent(array $context)
    {
        return "@nucleus/partials/particle.html.twig";
    }

    protected function doDisplay(array $context, array $blocks = [])
    {
        $this->parent->display($context, array_merge($this->blocks, $blocks));
    }

    // line 3
    public function block_particle($context, array $blocks = [])
    {
        // line 4
        echo "    <div class=\"g-date\">
        <span>";
        // line 5
        echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('date')->getCallable(), [$this->env, "now", (($this->getAttribute($this->getAttribute(($context["particle"] ?? null), "date", [], "any", false, true), "formats", [], "any", true, true)) ? (_twig_default_filter($this->getAttribute($this->getAttribute(($context["particle"] ?? null), "date", [], "any", false, true), "formats", []), "l, F d, Y")) : ("l, F d, Y"))]), "html", null, true);
        echo "</span>
    </div>
";
    }

    public function getTemplateName()
    {
        return "@particles/date.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  34 => 5,  31 => 4,  28 => 3,  11 => 1,);
    }

    /** @deprecated since 1.27 (to be removed in 2.0). Use getSourceContext() instead */
    public function getSource()
    {
        @trigger_error('The '.__METHOD__.' method is deprecated since version 1.27 and will be removed in 2.0. Use getSourceContext() instead.', E_USER_DEPRECATED);

        return $this->getSourceContext()->getCode();
    }

    public function getSourceContext()
    {
        return new Twig_Source("", "@particles/date.html.twig", "/home/dobrod/airbagua.com/www/media/gantry5/engines/nucleus/particles/date.html.twig");
    }
}
