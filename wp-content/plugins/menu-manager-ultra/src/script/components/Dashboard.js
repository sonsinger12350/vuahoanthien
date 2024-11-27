import React, { useContext, useRef, useState, useEffect } from 'react';
import MenuItemList from './MenuItemList';
import IconButton from './core/IconButton';
import ModalAddMenu from './ModalAddMenu';
import ModalSettings from './ModalSettings';
import { MenuService } from '../services/MenuService';
import { StoreContext } from '../utils/store';
import { AppConfig } from "../config/AppConfig";
import appDebug from "../utils/appDebug";

const Dashboard = () => {
	const [menuOptions, setMenuOptions] = useState([]);
  const [showAddMenu, setShowAddMenu] = useState(false);
	const [addMenuMessage, setAddMenuMessage] = useState("");
	const [showSettings, setShowSettings] = useState(false);
	const newMenuNameInputEl = useRef(null);

	const store = useContext(StoreContext);

	const handleAddMenuCancel = () => {
		setShowAddMenu(false);
	}

	const handleAddMenuSave = async () => {
		appDebug(AppConfig.debugCategoryDefault, 'save menu item', newMenuNameInputEl.current.value);

		if (newMenuNameInputEl.current.value) {
			try {
				const new_id = await MenuService.addMenu(
					newMenuNameInputEl.current.value
				);

				loadMenus();
				store.setMenuID(new_id);
				setShowAddMenu(false);
			}
			catch(e) {
				appDebug(AppConfig.debugCategoryDefault, 'Error saving menu', e);
				setAddMenuMessage(e.message);
			}
		}
	}

	const loadMenus = async () => {

		let menus = await MenuService.listMenus();

		setMenuOptions(
			menus.map(
				(menu) => 
				({
					label: menu.name,
					value: menu.id
				})
			)
		);
	}

  useEffect(
    () => {
			loadMenus();
    }, []
  );  	

	return (
    <div className="mmu-dashboard">
			<div className="mmu-settings-bar">
				<IconButton clickHandler={() => setShowSettings(true)} border={false} background={false} name="settings" icon="admin-generic">Custom Fields</IconButton>
			</div>
			<div className="mmu-toolbar">
				<div className="mmu-option-container">
					<label htmlFor="select-menu">Choose a Menu to Edit</label>
          <select value={store.menuID ?? ""} onChange={(e) => { store.reset(); store.setMenuID(e.target.value)} }>
            <option value="">Choose</option>
            {menuOptions.map((option) => (
              <option key={option.value} value={option.value}>{option.label}</option>
            ))}
          </select>
				</div>
				<div className="mmu-actions-container">
					<IconButton onClick={() => setShowAddMenu(true)} name="add" icon="plus">Add Menu</IconButton>
				</div>
			</div>
      <MenuItemList menuID={store.menuID} />
			<ModalAddMenu message={addMenuMessage} inputRef={newMenuNameInputEl} isOpen={showAddMenu} handleCancel={handleAddMenuCancel} handleSave={handleAddMenuSave} />
			<ModalSettings handleCancel={() => setShowSettings(false)} setShowSettings={setShowSettings} isOpen={showSettings}></ModalSettings>
    </div>
	);
}

export default Dashboard;