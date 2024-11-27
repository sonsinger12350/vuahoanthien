<?php

function woo_importer_get_items( $objWorksheet, $sheetName = '' )
{
    if( !$objWorksheet ) {
        return [];
    }
    
    $list_name = [];

    for( $col = 0; $col < 300; $col++ ) {
        $cell = $objWorksheet->getCellByColumnAndRow( $col, 1);
        $value = $cell->getValue();

        if( is_null($value) ) {
            break;
        }
        
        $list_name[] = sanitize_text_field($value);
    }
    
    $col_n = count($list_name);

    $items = [];
    for( $row = 2; $row < 3000; $row++ ) {
        $empty = 0;

        for( $col = 0; $col < $col_n; $col++ ) {
            $name   = $list_name[ $col ];
            $value = woo_importer_cell_value($objWorksheet->getCellByColumnAndRow($col, $row));
            
            if( $value == '' ) {
                $empty++;
            }
            
            $item[$name] = $value;
        }

        if( $col_n != $empty ) {
            $items[] = $item;
        }
    }
    
    return $items;
}

function woo_importer_cell_value( $cell = false )
{
    $value  = $cell->getValue();

    if( is_null($value) ) {
        $value = '';
    }

    if( substr($value,0,1) == '=' && strlen($value) > 1 ) {
        $value = $cell->getCalculatedValue();
    }

    return $value;
}

function woo_importer_cell_lines( $value = '' )
{
    $list = explode("\n", $value);

    foreach( $list as $i => $v ) {
        $list[$i] = trim($v);
    }

    return $list;
}