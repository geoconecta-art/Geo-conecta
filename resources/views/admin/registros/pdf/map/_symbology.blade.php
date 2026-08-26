<table class="w-100" id="simbology_table">
    <tr class="w-100">
        <td colspan="12">
            <div class="text-center text-upercase w-100">
                simbología
            </div>
        </td>
    </tr>

    @foreach ($plans as $plan)
        <tr>
            <td colspan="1">
                @if ( $plan->geometry == "Point" )
                    <div class="punto" style="background-color: {{ str_contains($plan->color, "#") ? $plan->color : ("#". $plan->color) }};"></div>
                @else
                    <div class="line" style="border: 2px solid {{ str_contains($plan->color, "#") ? $plan->color : ("#". $plan->color) }};"></div>
                @endif
            </td>
            <td colspan="11">
                <span class="fs-xs">
                    {{ $plan->name }}
                </span>
            </td>
        </tr>
    @endforeach
</table>