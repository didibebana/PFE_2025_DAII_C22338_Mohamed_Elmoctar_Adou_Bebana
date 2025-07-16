import { Ziggy } from '@/ziggy';
import Pagination from '@/Components/pagination';
import TextInput from '@/Components/TextInput';
import  SelectInput  from '@/Components/SelectInput';
import { ChevronUpIcon, ChevronDownIcon } from '@heroicons/react/16/solid';
import { Link, router, usePage } from '@inertiajs/react';
export default function ActionTable({ actions, queryParams = null }) {
    queryParams = queryParams || {}
    const searchFieldChanged = (nom, value) => {
        if(value){
            queryParams[nom] = value
        } else {
            delete queryParams[nom]
        }

        router.get(route("action.index", queryParams))
    }

    const onKeyPress = (nom, e) => {
        if(e.key !== 'Enter') return;

        searchFieldChanged(nom, e.target.value);
    }

    const sortChanged = (nom) => {
        if(nom === queryParams.sort_field) {
            if(queryParams.sort_direction === 'asc') {
                queryParams.sort_direction = 'desc';
            } else {
                queryParams.sort_direction = 'asc';
            }
        } else {
            queryParams.sort_field = nom;
            queryParams.sort_direction = 'asc';
        }

        router.get(route("action.index", queryParams))
    }
    const deleteAction = (action) => {
        if(!window.confirm('Etes-vous sûr de vouloir supprimer cette action ?')) {
            return;
        }
        router.delete(route("action.destroy", action.id))
    }
    const user = usePage().props.auth.user;

    const isAdmin = user?.role?.nom === 'administrateur';
    const isCoordinateur = user?.role?.nom === 'coordinateur_axe';
    const isResponsable = user?.role?.nom === 'responsable_action';

    return (
        <>
            <div className="overflow-hidden bg-white shadow-sm sm:rounded-lg dark:bg-gray-800">
                        <div className="p-6 text-gray-900 dark:text-gray-100">
                            <table className="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                                <thead className="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400 border-b-2 border-gray-500">
                                    <tr className="text-nowrap">
                                    <th onClick={(e) => sortChanged("id")} className="px-3 py-2"><div className=" flex items-center justify-between gap-1 cursor-pointer">ID <div><ChevronUpIcon className="w-4" /><ChevronDownIcon className="w-4 -mt-2" /></div></div></th>
                                    <th onClick={(e) =>sortChanged("nom")} className="px-3 py-2"><div className="flex items-center justify-between gap-1 cursor-pointer">Nom <div><ChevronUpIcon className="w-4" /><ChevronDownIcon className="w-4 -mt-2" /></div></div></th>
                                    <th className="px-3 py-2"><div className=" flex items-center justify-between gap-1 cursor-pointer">Nom du sous axe </div></th>
                                    <th className="px-3 py-2"><div className=" flex items-center justify-between gap-1 cursor-pointer">Statut</div></th>
                                    <th className="px-3 py-2">Actions</th>
                                    </tr>
                                </thead>
                                <thead className="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400 border-b-2 border-gray-500">
                                    <tr className="text-nowrap">
                                        <th className="px-3 py-2"></th>
                                        <th className="px-3 py-2">
                                            <TextInput className="w-full"
                                                defaultValue={queryParams.nom}
                                                placeholder="chercher avec le nom du l'action"
                                                onBlur={e =>{ searchFieldChanged("nom", e.target.value)}}
                                                onKeyPress={e => onKeyPress("nom", e)}
                                            />
                                        </th>
                                        <th className="px-3 py-2"></th>
                                        <th className="px-3 py-2">
                                            <SelectInput className="w-full"
                                                defaultValue={queryParams.statut}
                                                onChange={(e) => searchFieldChanged("statut", e.target.value)} >
                                                    <option value="Tous">Tous</option>
                                                    <option value="En cours">En cours</option>
                                                    <option value="Terminée">Terminée</option>
                                                    <option value="En attente">En attente</option>
                                                    <option value="En retard">En retard</option>
                                                    <option value="A venir">A venir</option>
                                                    <option value="Annulée">Annulée</option>
                                            </SelectInput>
                                        </th>
                                        <th className="px-3 py-2"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                {actions.data && actions.data.map((action) => (
                                    <tr key={action.id} className="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                        <td className="px-3 py-2">{action.id}</td>
                                        <td className="px-3 py-2">{action.nom}</td>
                                        <td className="px-3 py-2">{action.sous_axe.nom}</td>
                                        <td className="px-3 py-2">
                                            <span className={
                                                    action.statut === 'Terminée' ? 'text-green-600' :
                                                    action.statut === 'En cours' ? 'text-yellow-500' :
                                                    action.statut === 'En attente' ? 'text-blue-600' :
                                                    action.statut === 'A venir' ? 'text-indigo-600' :
                                                    action.statut === 'En retard' ? 'text-gray-600' :
                                                    'text-red-600'}>
                                                {action.statut}
                                            </span>
                                        </td>
                                        <td className="px-3 py-2 text-nowrap">
                                            <Link href={route('action.show', action.id, false, Ziggy)} className="font-medium text-green-600 dark:text-green-500 hover:underline mr-4">Détails</Link>
                                           {(isAdmin || isResponsable) && (<> <Link href={route('action.edit', action.id)} className="font-medium text-blue-600 dark:text-blue-500 hover:underline mr-4">Modifier</Link></>)}
                                           {(isAdmin || isCoordinateur) && (<> <button onClick={(e) => deleteAction(action)} className="font-medium text-red-600 dark:text-red-500 hover:underline mr-4">Supprimer</button> </> )}
                                        </td>
                                    </tr>
                                ))}
                                </tbody>

                            </table>
                            <Pagination links={actions.meta.links} />
                        </div>
                    </div>
        </>
    )
}
