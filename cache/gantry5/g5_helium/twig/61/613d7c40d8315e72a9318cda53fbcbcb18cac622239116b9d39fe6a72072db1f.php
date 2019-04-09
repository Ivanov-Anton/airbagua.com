<?php

/* @particles/assets.html.twig */
class __TwigTemplate_54c19280d8deda20e20b6df3dda20c68d73560f39194a8ffb6786109a9d837a3 extends Twig_Template
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
        ob_start();
        // line 2
        if ($this->getAttribute(($context["particle"] ?? null), "enabled", [])) {
            // line 3
            echo "    ";
            $context['_parent'] = $context;
            $context['_seq'] = twig_ensure_traversable($this->getAttribute(($context["particle"] ?? null), "css", []));
            foreach ($context['_seq'] as $context["_key"] => $context["css"]) {
                // line 4
                echo "        ";
                $context["params"] = [];
                // line 5
                echo "        ";
                if ($this->getAttribute($context["css"], "extra", [])) {
                    // line 6
                    echo "            ";
                    $context['_parent'] = $context;
                    $context['_seq'] = twig_ensure_traversable($this->getAttribute($context["css"], "extra", []));
                    foreach ($context['_seq'] as $context["_key"] => $context["attributes"]) {
                        // line 7
                        echo "                ";
                        $context['_parent'] = $context;
                        $context['_seq'] = twig_ensure_traversable($context["attributes"]);
                        foreach ($context['_seq'] as $context["key"] => $context["value"]) {
                            // line 8
                            echo "                    ";
                            $context["params"] = twig_array_merge((((isset($context["params"]) || array_key_exists("params", $context))) ? (_twig_default_filter(($context["params"] ?? null), [])) : ([])), [$context["key"] => $context["value"]]);
                            // line 9
                            echo "                ";
                        }
                        $_parent = $context['_parent'];
                        unset($context['_seq'], $context['_iterated'], $context['key'], $context['value'], $context['_parent'], $context['loop']);
                        $context = array_intersect_key($context, $_parent) + $_parent;
                        // line 10
                        echo "            ";
                    }
                    $_parent = $context['_parent'];
                    unset($context['_seq'], $context['_iterated'], $context['_key'], $context['attributes'], $context['_parent'], $context['loop']);
                    $context = array_intersect_key($context, $_parent) + $_parent;
                    // line 11
                    echo "        ";
                }
                // line 12
                echo "
        ";
                // line 13
                if ($this->getAttribute($context["css"], "location", [])) {
                    // line 14
                    echo "            ";
                    $this->getAttribute($this->getAttribute(($context["gantry"] ?? null), "document", []), "addStyle", [0 => twig_array_merge((((isset($context["params"]) || array_key_exists("params", $context))) ? (_twig_default_filter(($context["params"] ?? null), [])) : ([])), ["href" => $this->getAttribute($context["css"], "location", [])]), 1 => $this->getAttribute($context["css"], "priority", [])], "method");
                    // line 15
                    echo "        ";
                }
                // line 16
                echo "
        ";
                // line 17
                if ($this->getAttribute($context["css"], "inline", [])) {
                    // line 18
                    echo "            ";
                    $this->getAttribute($this->getAttribute(($context["gantry"] ?? null), "document", []), "addInlineStyle", [0 => twig_array_merge((((isset($context["params"]) || array_key_exists("params", $context))) ? (_twig_default_filter(($context["params"] ?? null), [])) : ([])), ["content" => $this->getAttribute($context["css"], "inline", [])]), 1 => $this->getAttribute($context["css"], "priority", [])], "method");
                    // line 19
                    echo "        ";
                }
                // line 20
                echo "    ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['css'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 21
            echo "
    ";
            // line 22
            $context['_parent'] = $context;
            $context['_seq'] = twig_ensure_traversable($this->getAttribute(($context["particle"] ?? null), "javascript", []));
            foreach ($context['_seq'] as $context["_key"] => $context["script"]) {
                // line 23
                echo "        ";
                $context["params"] = [];
                // line 24
                echo "        ";
                if ($this->getAttribute($context["script"], "extra", [])) {
                    // line 25
                    echo "            ";
                    $context['_parent'] = $context;
                    $context['_seq'] = twig_ensure_traversable($this->getAttribute($context["script"], "extra", []));
                    foreach ($context['_seq'] as $context["_key"] => $context["attributes"]) {
                        // line 26
                        echo "                ";
                        $context['_parent'] = $context;
                        $context['_seq'] = twig_ensure_traversable($context["attributes"]);
                        foreach ($context['_seq'] as $context["key"] => $context["value"]) {
                            // line 27
                            echo "                    ";
                            $context["params"] = twig_array_merge((((isset($context["params"]) || array_key_exists("params", $context))) ? (_twig_default_filter(($context["params"] ?? null), [])) : ([])), [$context["key"] => $context["value"]]);
                            // line 28
                            echo "                ";
                        }
                        $_parent = $context['_parent'];
                        unset($context['_seq'], $context['_iterated'], $context['key'], $context['value'], $context['_parent'], $context['loop']);
                        $context = array_intersect_key($context, $_parent) + $_parent;
                        // line 29
                        echo "            ";
                    }
                    $_parent = $context['_parent'];
                    unset($context['_seq'], $context['_iterated'], $context['_key'], $context['attributes'], $context['_parent'], $context['loop']);
                    $context = array_intersect_key($context, $_parent) + $_parent;
                    // line 30
                    echo "        ";
                }
                // line 31
                echo "
        ";
                // line 32
                if ($this->getAttribute($context["script"], "location", [])) {
                    // line 33
                    echo "            ";
                    $this->getAttribute($this->getAttribute(($context["gantry"] ?? null), "document", []), "addScript", [0 => twig_array_merge((((isset($context["params"]) || array_key_exists("params", $context))) ? (_twig_default_filter(($context["params"] ?? null), [])) : ([])), ["src" => $this->getAttribute($context["script"], "location", [])]), 1 => $this->getAttribute($context["script"], "priority", []), 2 => ((($this->getAttribute($context["script"], "in_footer", []) == true)) ? ("footer") : ("head"))], "method");
                    // line 34
                    echo "        ";
                }
                // line 35
                echo "
        ";
                // line 36
                if ($this->getAttribute($context["script"], "inline", [])) {
                    // line 37
                    echo "            ";
                    $this->getAttribute($this->getAttribute(($context["gantry"] ?? null), "document", []), "addInlineScript", [0 => twig_array_merge((((isset($context["params"]) || array_key_exists("params", $context))) ? (_twig_default_filter(($context["params"] ?? null), [])) : ([])), ["content" => $this->getAttribute($context["script"], "inline", [])]), 1 => $this->getAttribute($context["script"], "priority", []), 2 => ((($this->getAttribute($context["script"], "in_footer", []) == true)) ? ("footer") : ("head"))], "method");
                    // line 38
                    echo "        ";
                }
                // line 39
                echo "    ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['script'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
        }
        echo trim(preg_replace('/>\s+</', '><', ob_get_clean()));
    }

    public function getTemplateName()
    {
        return "@particles/assets.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  153 => 39,  150 => 38,  147 => 37,  145 => 36,  142 => 35,  139 => 34,  136 => 33,  134 => 32,  131 => 31,  128 => 30,  122 => 29,  116 => 28,  113 => 27,  108 => 26,  103 => 25,  100 => 24,  97 => 23,  93 => 22,  90 => 21,  84 => 20,  81 => 19,  78 => 18,  76 => 17,  73 => 16,  70 => 15,  67 => 14,  65 => 13,  62 => 12,  59 => 11,  53 => 10,  47 => 9,  44 => 8,  39 => 7,  34 => 6,  31 => 5,  28 => 4,  23 => 3,  21 => 2,  19 => 1,);
    }

    /** @deprecated since 1.27 (to be removed in 2.0). Use getSourceContext() instead */
    public function getSource()
    {
        @trigger_error('The '.__METHOD__.' method is deprecated since version 1.27 and will be removed in 2.0. Use getSourceContext() instead.', E_USER_DEPRECATED);

        return $this->getSourceContext()->getCode();
    }

    public function getSourceContext()
    {
        return new Twig_Source("", "@particles/assets.html.twig", "/home/dobrod/airbagua.com/www/media/gantry5/engines/nucleus/particles/assets.html.twig");
    }
}
