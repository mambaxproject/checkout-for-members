@props([
    'url',
    'color' => 'primary',
    'align' => 'center',
])
<table class="action" align="{{ $align }}" width="100%" cellpadding="0" cellspacing="0" role="presentation">
<tr>
<td align="{{ $align }}">
<table width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation">
<tr>
<td align="{{ $align }}">
<table border="0" cellpadding="0" cellspacing="0" role="presentation">
<tr>
<td>
<a href="{{ $url }}" 
style="mso-style-priority: 100 !important;
text-decoration: none;
-webkit-text-size-adjust: none;
-ms-text-size-adjust: none;
mso-line-height-rule: exactly;
color: #ffffff;
font-size: 18px;
padding: 15px 35px 15px 35px;
display: inline-block;
background: #3ecc33;
border-radius: 10px;
font-family: Poppins,
sans-serif;
font-weight: bold;
font-style: normal;
line-height: 22px;
width: auto;
text-align: center;" target="_blank" rel="noopener">{{ $slot }}</a>
</td>
</tr>
</table>
</td>
</tr>
</table>
</td>
</tr>
</table>
