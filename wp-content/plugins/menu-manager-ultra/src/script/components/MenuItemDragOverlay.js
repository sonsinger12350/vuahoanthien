import React, {forwardRef} from 'react';

export const MenuItemDragOverlay = forwardRef(({id, item, ...props}, ref) => {
  
  return (
    
    <div style={{'padding': '20px', 'width': '60%', 'background-color': '#f1f1f1', 'border': '2px dashed #CCC'}} className="mmu-menu-item-container mmu-menu-item-container--is-being-dragged" {...props} ref={ref}>
      <div className="mmu-menu-item-single">
        <div className="mmu-menu-item-summary">
          <div className="mmu-menu-item-title">{item.title}</div>
        </div>  
      </div>
    </div>
  )
});

