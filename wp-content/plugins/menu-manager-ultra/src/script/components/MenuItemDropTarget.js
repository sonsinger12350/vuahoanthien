import React, { useMemo } from 'react';
import { useDroppable } from '@dnd-kit/core';

const MenuItemDropTarget = (props) => {

  const { position, item, ...rest } = props;

  const id = useMemo(
    () => {
     return `droppable-${position.toString()}-${item.ID.toString()}`;
    }, [position, item.ID]
  ) 

  const { setNodeRef, isOver, over } = useDroppable({
    id: id,
    data: {
      item: item
    }
  });

  return (
    <div ref={setNodeRef} id={id} className={`mmu-droppable-container mmu-droppable-container--${position} ` + (isOver ? ` mmu-droppable-container--active mmu-droppable-container--${position}--active` : "")} {...rest}>
      <div className={`mmu-droppable-inner mmu-droppable-inner--${position}`}></div>
    </div>
  );
}

export default MenuItemDropTarget;