
<div class="row">
    <div class="col-md-6 mb-3">
        <div class="form-group">
            <label class="form-label" for="region">Región *</label>
            <div class="form-control-wrap ">
                <div class="form-control-select">
                    <select class="form-control" 
                            name="region" required>
                        <option selected disabled value="">Selecciona una región</option>
                        @foreach ( $regions as $region )
                            <option value="{{ $region->region }}" dep="{{ $region->dependence }}"
                                @if ( $person->region == $region->region ) selected @endif >
                                {{ $region->region }}
                            </option>
                        @endforeach
                        
                    </select>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6 mb-3">
        @include('admin.people._training')
    </div>
</div>
