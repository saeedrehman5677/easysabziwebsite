@if(count($combinations) > 0)
    <table class="table table-bordered">
        <thead>
        <tr>
            <td class="text-center">
                <label for="" class="control-label">{{ translate('Variant') }}</label>
            </td>
            <td class="text-center">
                <label for="" class="control-label">{{ translate('Variant Price') }}</label>
            </td>
            <td class="text-center">
                <label for="" class="control-label">{{ translate('Variant Stock') }}</label>
            </td>
        </tr>
        </thead>
        <tbody>

        @foreach ($combinations as $key => $combination)
            <tr>
                <td>
                    <label for="" class="control-label">{{ $combination['type'] }}</label>
                </td>
                <td>
                    <input type="number" name="price_{{ $combination['type'] }}"
                           value="{{$combination['price']}}" min="0"
                           step="any"
                           class="form-control" required>
                </td>
                <td>
                    <input type="number" name="stock_{{ $combination['type'] }}" value="{{ $combination['stock']??0 }}"
                           min="0" max="1000000" onkeyup="update_qty()"
                           class="form-control" required>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
@endif
