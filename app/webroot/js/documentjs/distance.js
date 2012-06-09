steal.then(function() {
	// the distance between 2 strings
	DocumentJS.distance = function( s1, s2 ) {
		if ( s1 == s2 ) {
			return 0;
		}
		var s1_len = s1.length,
			s2_len = s2.length;
		if ( s1_len === 0 ) {
			return s2_len;
		}
		if ( s2_len === 0 ) {
			return s1_len;
		}

		s1 = s1.split('');
		s2 = s2.split('');


		var v0 = new Array(s1_len + 1),
			v1 = new Array(s1_len + 1),
			s1_idx = 0,
			s2_idx = 0,
			cost = 0;
		for ( s1_idx = 0; s1_idx < s1_len + 1; s1_idx++ ) {
			v0[s1_idx] = s1_idx;
		}
		var char_s1 = '',
			char_s2 = '';
		for ( s2_idx = 1; s2_idx <= s2_len; s2_idx++ ) {
			v1[0] = s2_idx;
			char_s2 = s2[s2_idx - 1];

			for ( s1_idx = 0; s1_idx < s1_len; s1_idx++ ) {
				char_s1 = s1[s1_idx];
				cost = (char_s1 == char_s2) ? 0 : 1;
				var m_min = v0[s1_idx + 1] + 1;
				var b = v1[s1_idx] + 1;
				var c = v0[s1_idx] + cost;
				if ( b < m_min ) {
					m_min = b;
				}
				if ( c < m_min ) {
					m_min = c;
				}
				v1[s1_idx + 1] = m_min;
			}
			var v_tmp = v0;
			v0 = v1;
			v1 = v_tmp;
		}
		return v0[s1_len];
	}
})